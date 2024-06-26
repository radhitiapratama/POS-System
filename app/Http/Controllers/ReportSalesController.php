<?php

namespace App\Http\Controllers;

use App\Models\SaleDetails;
use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReportSalesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $index = 0;
            $filter_start_date = null;
            $filter_end_date = null;
            $filter_no_inv = $request->filter_no_inv;

            if ($request->filter_start_date != null && $request->filter_end_date != null) {
                $filter_start_date = Carbon::parse($request->input("filter_start_date"))->format("Y-m-d 00:00:00");
                $filter_end_date = Carbon::parse($request->input("filter_end_date"))->format("Y-m-d 23:59:59");
            }

            $model = Sales::query()
                ->with("sale_details")
                ->addSelect([
                    'total_sales' => SaleDetails::select(DB::raw("SUM(qty * price)"))
                        ->whereColumn("sales.id", 'sale_details.sales_id')
                ]);


            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->filter(function ($q) use ($filter_start_date, $filter_end_date, $filter_no_inv) {
                    if ($filter_start_date != null && $filter_end_date != null) {
                        $q->where("created_at", ">=", $filter_start_date)
                            ->where("created_at", "<=", $filter_end_date);
                    }

                    if ($filter_no_inv != null) {
                        $q->where("number_invoice", $filter_no_inv);
                    }
                })
                ->editColumn("total_sales", function ($model) {
                    return "Rp " . number_format($model->total_sales, 0, ',', '.');
                })
                ->editColumn("created_at", function ($model) {
                    return Carbon::parse($model->created_at)->format("d-m-Y H:i:s");
                })
                ->addColumn('action', function ($model) use (&$index) {
                    $index++;
                    $action_el = '
                    <div class="hstack gap-2 fs-15 d-flex justify-content-center">
                        <a href="' . url("/sales/$model->id/print") . '"
                            class="btn btn-icon btn-sm btn-primary" target="_blank">
                            <i class="ri-printer-line"></i>
                        </a>
                        <a href="' . url("report/sales/$model->id") . '"
                            class="btn btn-icon btn-sm btn-info">
                            <i class="ri-history-line"></i>
                        </a>
                        <a href="' . url("sales/$model->id/return") . '"
                            class="btn btn-icon btn-sm btn-danger">
                            <i class="ri-text-wrap"></i>
                        </a>
                    </div>
                    ';

                    return $action_el;
                })
                ->toJson();
        }

        return view("pages.report.sales.index");
    }

    public function show(Request $request, $sales_id)
    {
        if ($sales_id == null) {
            return redirect()->back();
        }

        if ($request->ajax()) {
            $index = 0;

            $model = SaleDetails::with('product')
                ->where('sales_id', $sales_id);

            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->editColumn("qty", function ($model) {
                    return  number_format($model->qty, 0, ',', '.');
                })
                ->editColumn("price", function ($model) {
                    return "Rp " . number_format($model->price, 0, ',', '.');
                })
                ->editColumn("total_price", function ($model) {
                    return ($model->price * $model->qty);
                })
                ->toJson();
        }

        $sales = Sales::where("id", $sales_id)->first();

        return view("pages.report.sales.sale-details", [
            'sales' => $sales
        ]);
    }
}
