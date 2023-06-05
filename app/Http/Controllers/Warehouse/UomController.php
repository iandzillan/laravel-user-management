<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Uom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

use function GuzzleHttp\Promise\all;

class UomController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Uom::class);

        $uoms = Uom::latest()->get();
        if ($request->ajax()) {
            return DataTables::of($uoms)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $btn_edit   = '';
                    $btn_delete = '';
                    $btn_edit   = '<a id="btn-edit" data-id="' . $row->id . '" class="btn btn-info btn-sm" title="Edit"><i class="ti ti-edit"></i></a>';
                    $btn_delete = ' <a id="btn-delete" data-id="' . $row->id . '" class="btn btn-danger btn-sm" title="Delete"><i class="ti ti-trash"></i></a>';

                    return $btn_edit . $btn_delete;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('warehouse.uom.index', [
            'title' => 'UOM (Stock Unit)'
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Uom::class);

        $validator = Validator::make($request->all(), [
            'code' => 'required|min:3|max:3|alpha_num|unique:uoms',
            'name' => 'required',
            'unit' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $uom = Uom::create([
            'code' => $request->code,
            'name' => $request->name,
            'unit' => $request->unit,
        ]);

        return response()->json([
            'success' => true,
            'data'    => $uom
        ]);
    }

    public function show(Uom $uom)
    {
        $this->authorize('update', Uom::class);

        return response()->json([
            'success' => true,
            'data'    => $uom
        ]);
    }

    public function update(Request $request, Uom $uom)
    {
        $this->authorize('update', Uom::class);

        $validator = Validator::make($request->all(), [
            'code' => ['required', 'min:3', 'max:3', 'alpha_num', Rule::unique('uoms')->ignore($uom->id)],
            'name' => 'required',
            'unit' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $uom->update([
            'code' => $request->code,
            'name' => $request->name,
            'unit' => $request->unit,
        ]);

        return response()->json([
            'success' => true,
            'data'    => $uom
        ]);
    }

    public function destroy(Uom $uom)
    {
        $this->authorize('delete', Uom::class);

        $uom->delete();
        return response()->json([
            'success' => true,
            'data'    => $uom
        ]);
    }
}
