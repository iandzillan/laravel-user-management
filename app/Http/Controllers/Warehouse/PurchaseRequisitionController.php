<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Item;
use App\Models\PurchaseRequisition;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PurchaseRequisitionController extends Controller
{
    public function index(Request $request)
    {
        $prs = PurchaseRequisition::latest()->get();
        if ($request->ajax()) {
            return DataTables::of($prs)
                ->addIndexColumn()
                ->addColumn('requester', function ($row) {
                    return $row->employee->name;
                })
                ->addColumn('actions', function ($row) {
                    $btn_info   = '';
                    $btn_edit   = '';
                    $btn_delete = '';
                    $btn_info   = '<a id="btn-info" data-id="' . $row->id . '" class="btn btn-success btn-sm" title="Detail"></a>';
                    $btn_edit   = ' <a id="btn-edit" data-id="' . $row->id . '" class="btn btn-info btn-sm" title="Edit"></a>';
                    $btn_delete = ' <a id="btn-delete" data-id="' . $row->id . '" class="btn btn-danger btn-sm" title="Delete"></a>';

                    return $btn_info . $btn_edit . $btn_delete;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('warehouse.pr.index', [
            'title' => 'Purchase Requisition'
        ]);
    }

    public function create()
    {
        $employees = Employee::all();
        $items     = Item::where('stock', '>=', 'safety_stock')->get();

        return view('warehouse.pr.create', [
            'title'     => 'Create Purchase Requisition',
            'employees' => $employees,
            'items'     => $items
        ]);
    }

    public function store()
    {
    }

    public function show()
    {
    }

    public function update()
    {
    }

    public function destroy()
    {
    }
}
