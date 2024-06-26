<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SaleDetails;
use App\Models\Sales;
use App\Models\Stock;
use App\Models\StockHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Constraints\CountInDatabase;
use SebastianBergmann\CodeUnit\FunctionUnit;
use Stringable;

use function PHPUnit\Framework\returnSelf;

class CashierController extends Controller
{
    public function index(Request $request)
    {
        return view("pages.cashier.index");
    }

    public function __ajax__getProduct(Request $request)
    {
        if ($request->ajax()) {
            $product = $request->product;

            $data_product =  Product::with([
                'stock' => function ($q) {
                    $q->select("product_id", "stock");
                }
            ])
                ->where("barcode", $product)
                ->orWhere("name", $product)
                ->select("id", "name", "selling_price")
                ->first();

            return response()->json([
                'data' => $data_product
            ]);
        }
    }

    public function pay(Request $request)
    {
        // ubah cart menjadi collection
        $cart = collect($request->cart);

        // ambil semua produk cart dari db
        $products = Product::with([
            "stock" => function ($q) {
                $q->orderBy("created_at", "DESC");
            }
        ])
            ->whereIn("id", array_column($cart->toArray(), "id"))->get();
        $total_sales = 0;

        //hitung total belanja dan stock
        for ($i = 0; $i < count($cart); $i++) {
            $selected_product = $products->where("id", $cart[$i]['id'])->first();
            // dd($selected_product);
            if ($selected_product == null) {
                continue;
            }

            // cek qty product yg di beli lebih besar dari sisa stock
            if ($cart[$i]['qty'] > $selected_product->stock->stock) {
                return response()->json([
                    'status' => "error",
                    "title" => "Gagal",
                    'message' => "Stock produk " . $selected_product->name . " habis,silahkan coba lagi."
                ]);
            }

            $total_sales += $cart[$i]['qty'] * $selected_product->selling_price;
        }

        // cek kembalian
        $return_price = $request->pay - $total_sales;

        // uang belanja kurang
        if ($return_price < 0) {
            return response()->json([
                'status' => "error",
                "title" => "Gagal",
                'message' => "Uang yang di bayarkan tidak cukup!"
            ]);
        }

        try {
            DB::beginTransaction();

            $stock_history = [];
            $sales_details = [];
            $get_last_id_sales = Sales::select("id")->latest()->take(1)->first();
            $sales_id = 1;
            if ($get_last_id_sales != null) {
                $sales_id =  $get_last_id_sales->id++;
                $sales_id++;
            }

            for ($i = 0; $i < count($cart); $i++) {
                // ambil data produk dulu
                $selected_product = $products->where("id", $cart[$i]['id'])->first();
                // data stock_history
                $sub_stock_history['product_id'] = $selected_product->id;
                $sub_stock_history['type'] = "sale";
                $sub_stock_history['qty'] = $cart[$i]['qty'];
                $sub_stock_history['stock_before'] = $selected_product->stock->stock;
                $sub_stock_history['remaining_stock'] = $selected_product->stock->stock - $cart[$i]['qty'];
                $sub_stock_history['created_at'] = Carbon::now();
                $sub_stock_history['updated_at'] = Carbon::now();
                $stock_history[] = $sub_stock_history;

                Stock::where("product_id", $cart[$i]['id'])
                    ->update([
                        'stock' => $selected_product->stock->stock - $cart[$i]['qty']
                    ]);

                // data untuk table sale_details
                $sub_sale_details['sales_id'] = $sales_id;
                $sub_sale_details['product_id'] = $selected_product->id;
                $sub_sale_details['qty'] = $cart[$i]['qty'];
                $sub_sale_details['price'] = $selected_product->selling_price;
                $sub_sale_details['created_at'] = Carbon::now();
                $sub_sale_details['updated_at'] = Carbon::now();
                $sales_details[] = $sub_sale_details;
            }

            // data untuk table sales
            $num_inv = "INV" . date("YmdHis") . rand(0, 1000);
            $sales = [
                'number_invoice' => $num_inv,
                'pay' => $request->pay,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            //insert ket stock_history
            StockHistory::insert($stock_history);
            //insert ke sales
            Sales::insert($sales);
            //insert ke sale_details
            SaleDetails::insert($sales_details);

            DB::commit();

            return response()->json([
                'status' => "success",
                "title" => "Transaksi berhasil",
                "message" => "",
                'cart' => [],
                'sales_id' => $sales_id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => "error",
                "title" => "Gagal",
                'message' => "Transaksi gagal,silahkan coba lagi",
                'err_msg' => $e->getMessage(),
            ]);
        }
    }

    protected function calcTotalSales()
    {
    }
}
