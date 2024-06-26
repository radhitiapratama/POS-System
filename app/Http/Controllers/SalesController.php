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
}
