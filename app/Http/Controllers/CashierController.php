<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
}
