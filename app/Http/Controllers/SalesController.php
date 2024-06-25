<?php

namespace App\Http\Controllers;

use App\Models\SaleDetails;
use App\Models\Sales;
use Illuminate\Http\Request;

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
}
