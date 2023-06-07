<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Uom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Item::class);

        $items = Item::latest()->get();
        if ($request->ajax()) {
            return DataTables::of($items)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->category->name;
                })
                ->addColumn('uom', function ($row) {
                    return $row->uom->unit;
                })
                ->addColumn('actions', function ($row) use ($request) {
                    $btn_detail = '';
                    $btn_edit   = '';
                    $btn_delete = '';
                    if ($request->user()->can('view', Item::class)) {
                        $btn_detail = '<a id="btn-detail" data-id="' . $row->id . '" class="btn btn-success btn-sm" title="Detail"><i class="ti ti-search"></i></a>';
                    }
                    if ($request->user()->can('update', Item::class)) {
                        $btn_edit   = ' <a id="btn-edit" data-id="' . $row->id . '" class="btn btn-info btn-sm" title="Edit"><i class="ti ti-edit"></i></a>';
                    }
                    if ($request->user()->can('delete', Item::class)) {
                        $btn_delete = ' <a id="btn-delete" data-id="' . $row->id . '" class="btn btn-danger btn-sm" title="Delete"><i class="ti ti-trash"></i></a>';
                    }

                    return $btn_detail . $btn_edit . $btn_delete;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('warehouse.item.index', [
            'title' => 'Items'
        ]);
    }

    public function getCategories(Request $request)
    {
        if ($request->user()->can('create', Item::class) || $request->user()->can('update', Item::class)) {
            $categories = Category::all();
            return response()->json($categories);
        }

        return abort(403);
    }

    public function getCategory(Request $request, Category $category)
    {
        if ($request->user()->can('create', Item::class) || $request->user()->can('update', Item::class)) {
            return response()->json($category);
        }

        return abort(403);
    }

    public function getUoms(Request $request)
    {
        if ($request->user()->can('create', Item::class) || $request->user()->can('update', Item::class)) {
            $uoms = Uom::all();
            return response()->json($uoms);
        }

        return abort(403);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Item::class);

        $validator = Validator::make($request->all(), [
            'code'         => 'required|unique:items|min:4|max:4',
            'name'         => 'required',
            'category_id'  => 'required',
            'location'     => 'required',
            'uom_id'       => 'required',
            'stock'        => 'required|integer|gte:safety_stock',
            'safety_stock' => 'required|integer|lte:stock',
            'desc'         => 'required',
            'status'       => 'sometimes',
            'qrcode'       => 'sometimes'
        ], [
            'category_id.required' => "Please choose the item's category",
            'uom_id.required'      => "Please choose the item's uom",
            'desc.required'        => "The description field is required"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category           = Category::find($request->category_id);
        $uom                = Uom::find($request->uom_id);
        $item               = new Item();
        $item->code         = $category->code . $request->code;
        $item->name         = $request->name;
        $item->location     = $request->location;
        $item->stock        = $request->stock;
        $item->safety_stock = $request->safety_stock;
        $item->desc         = $request->desc;
        $item->status       = $request->status;
        $image              = QrCode::format('svg')->size(200)->generate($item->code);
        $image_name         = 'qrcode-' . $item->code . '.svg';
        $item->qrcode       = $image_name;
        $output_file        = 'public/qr-code/' . $image_name;
        Storage::disk('local')->put($output_file, $image);
        $item->category()->associate($category);
        $item->uom()->associate($uom);
        $item->save();

        return response()->json([
            'success' => true,
            'data'    => $item
        ]);
    }

    public function show(Item $item)
    {
        $this->authorize('update', Item::class);

        return response()->json([
            'success'  => true,
            'data'     => $item,
            'category' => $item->category,
            'uom'      => $item->uom
        ]);
    }

    public function update(Request $request, Item $item)
    {
        $this->authorize('update', Item::class);

        $validator = Validator::make($request->all(), [
            'code'         => ['required', 'min:4', 'max:4', 'alpha_num', Rule::unique('items')->ignore($item->id)],
            'name'         => 'required',
            'category_id'  => 'required',
            'location'     => 'required',
            'uom_id'       => 'required',
            'stock'        => 'required|integer|gte:safety_stock',
            'safety_stock' => 'required|integer|lte:stock',
            'desc'         => 'required',
            'status'       => 'sometimes',
            'qrcode'       => 'sometimes'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category           = Category::find($request->category_id);
        $uom                = Uom::find($request->uom_id);
        $item->code         = $category->code . $request->code;
        $item->name         = $request->name;
        $item->location     = $request->location;
        $item->stock        = $request->stock;
        $item->safety_stock = $request->safety_stock;
        $item->desc         = $request->desc;
        $item->status       = $request->status;

        if ($item->qrcode == null) {
            $image          = QrCode::format('svg')->size(200)->generate($item->code);
            $image_name     = 'qrcode-' . $item->code . '.svg';
            $output_file    = 'public/qr-code/' . $image_name;
            Storage::disk('local')->put($output_file, $image);
        } else {
            $image_name     = 'qrcode-' . $item->code . '.svg';
            Storage::move('public/qr-code/' . $item->qrcode, 'public/qr-code/' . $image_name);
        }

        $item->qrcode       = $image_name;
        $item->category()->associate($category);
        $item->uom()->associate($uom);
        $item->save();

        return response()->json([
            'success' => true,
            'data'    => $item
        ]);
    }

    public function destroy(Item $item)
    {
        $this->authorize('delete', Item::class);

        $item->delete();
        Storage::delete('public/qr-code/' . $item->qrcode);
        return response()->json([
            'success' => true,
            'data'    => $item
        ]);
    }
}
