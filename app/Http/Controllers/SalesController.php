<?php

namespace App\Http\Controllers;

use App\Models\SaleDetails;
use App\Models\SaleReturn;
use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $filter_start_date = null;
            $filter_end_date = null;
            $filter_no_inv = $request->filter_no_inv;
            $filter_product = $request->filter_product;

            if ($request->filter_start_date != null && $request->filter_end_date != null) {
                $filter_start_date = date("Y-m-d 00:00:00", strtotime($request->filter_start_date));
                $filter_end_date = date("Y-m-d 23:59:59", strtotime($request->filter_end_date));
            }

            $model  = SaleReturn::query()
                ->select("*", DB::raw("(qty * price) as total_return"))
                ->with(['sale_details' => function ($q) {
                    $q->join("products", 'products.id', '=', 'sale_details.product_id')
                        ->join("sales", "sales.id", '=', 'sale_details.sales_id')
                        ->select("sale_details.*", "products.name", "sales.number_invoice");
                }]);

            $index = 0;

            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->filter(function ($query) use ($filter_start_date, $filter_end_date, $filter_product, $filter_no_inv) {
                    if ($filter_start_date != null && $filter_end_date != null) {
                        $query->where("created_at", ">=", $filter_start_date)
                            ->where("created_at", "<=", $filter_end_date);
                    }

                    if ($filter_product != null) {
                        $query->whereHas("sale_details", function ($q) use ($filter_product) {
                            $q->where("product_id", $filter_product);
                        });
                    }

                    if ($filter_no_inv != null) {
                        $query->whereHas("sale_details", function ($q) use ($filter_no_inv) {
                            $q->join("sales", "sales.id", '=', 'sale_details.id')
                                ->where("sales.number_invoice", $filter_no_inv);
                        });
                    }
                })
                ->editColumn("created_at", function ($model) {
                    return Carbon::parse($model->created_at)->format("d-m-Y H:i:s");
                })
                ->addColumn('action', function ($model) use (&$index) {
                    $index++;
                    $action_el = '
                    <div class="hstack gap-2 fs-15 d-flex justify-content-center align-items-center">
                        <a href="' . url("sales/return/$model->id/show") . '"
                        class="btn btn-icon btn-sm btn-info">
                            <i class="ri-eye-line"></i>
                        </a>
                    </div>
                    ';

                    return $action_el;
                })
                ->toJson();
        }

        return view("pages.sales.return.index");
    }

    public function print(int $sales_id)
    {
        $sales = Sales::where("id", $sales_id)->first();

        $sale_details =  SaleDetails::with(['product'])
            ->where("sales_id", $sales_id)
            ->get();

        return view("pages.sales.print", [
            'sale_details' => $sale_details,
            'sales' => $sales,
        ]);
    }

    public function returnSales(Request $request, $sales_id)
    {
        $sales = Sales::select("number_invoice")
            ->where("id", $sales_id)
            ->first();

        $sale_details = SaleDetails::with("product")
            ->where("sales_id", $sales_id)
            ->having("qty", ">", 0)
            ->get();

        return view("pages.sales.return.return-sales", [
            'sales' => $sales,
            'sale_details' => $sale_details,
        ]);
    }

    public function handleReturn(Request $request)
    {
        try {
            DB::beginTransaction();
            $ids = $request->id;
            $qty_returns = $request->qty_return;
            $return_messsage = $request->return_message;
            $db_sale_details = SaleDetails::whereIn("id", $ids)
                ->get();

            $sale_return = [];

            for ($i = 0; $i < count($db_sale_details); $i++) {
                //update data qty di table sale_details
                SaleDetails::where("id", $ids[$i])
                    ->update([
                        'is_return' => true,
                        'qty' => $db_sale_details[$i]->qty - $qty_returns[$i]
                    ]);

                $sale_return[] = [
                    'sale_detail_id' => $ids[$i],
                    'qty' => $qty_returns[$i],
                    'price' => $db_sale_details[$i]->price,
                    'return_message' => $return_messsage[$i],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            //insert to tbl sale_returns
            SaleReturn::insert($sale_return);

            DB::commit();

            return response()->json([
                'status' => "success",
                'message' => "Produk berhasil di retur",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => "error",
                'message' => "Terjadi kesalahan saat melakukan retur,silahkan coba lagi",
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    public function show($sale_return_id)
    {
        $sale_return = SaleReturn::with([
            'sale_details' => function ($q) {
                $q->with('product', 'sales');
            }
        ])
            ->where("id", $sale_return_id)
            ->first();

        return view("pages.sales.return.show", [
            'sale_return' => $sale_return,
        ]);
    }
}
