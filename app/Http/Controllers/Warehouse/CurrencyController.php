<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        $currencies = Currency::latest()->get();

        if ($request->ajax()) {
            return DataTables::of($currencies)
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

        return view('warehouse.currency.index', [
            'title' => 'Currency'
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $currency = Currency::create([
            'code' => $request->code,
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true,
            'data'    => $currency
        ]);
    }

    public function show(Currency $currency)
    {
        return response()->json([
            'success' => true,
            'data'    => $currency
        ]);
    }

    public function update(Request $request, Currency $currency)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $currency->update([
            'code' => $request->code,
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true,
            'data'    => $currency
        ]);
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();

        return response()->json([
            'success' => true,
            'data'    => $currency
        ]);
    }
}
