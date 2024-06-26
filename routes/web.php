<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportSalesController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::middleware("guest")->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name("login");
    Route::post("/login", [AuthController::class, 'handleLogin']);
});

Route::middleware("auth")->group(function () {
    //resource
    Route::resource("product-category", ProductCategoryController::class)->except("show");
    Route::resource("unit", UnitController::class)->except("show");
    Route::resource("product", ProductController::class)->except("show");

    // product
    Route::prefix("product")->group(function () {
        Route::match(['get', 'post'], 'litte-stock', [ProductController::class, 'littleStock']);
        Route::match(['get', 'post'], "best-selling", [ProductController::class, 'bestSelling']);
    });


    // stock
    Route::prefix('stock')->group(function () {
        Route::get("/", [StockController::class, 'index']);
        Route::get("create", [StockController::class, 'create']);
        Route::post("store", [StockController::class, 'store']);
        Route::get('stock-adjustment', [StockController::class, 'stockAdjustment']);
        Route::post("stock-adjustment/store", [StockController::class, 'stockAdjustmentStore']);
        Route::get("{product_id}/history", [StockController::class, 'history']);
    });

    //cashier
    Route::get("cashier", [CashierController::class, 'index']);
    Route::post("cashier/product", [CashierController::class, '__ajax__getProduct']);
    Route::post("cashier/pay", [CashierController::class, 'pay']);

    //sales
    Route::prefix("sales")->group(function () {
        Route::get("{sale_id}/print", [SalesController::class, 'print']);
        Route::match(['get', 'post'], "{sales_id}/return", [SalesController::class, 'returnSales']);
        Route::post("return/confirm", [SalesController::class, 'handleReturn']);
        Route::match(['get', 'post'], 'return', [SalesController::class, 'index']);
        Route::get("return/{sales_return_id}/show", [SalesController::class, 'show']);
    });


    //report
    Route::prefix("report")->group(function () {
        Route::match(['get', 'post'], "sales", [ReportSalesController::class, 'index']);
        Route::match(['get', 'post'], "sales/{sales_id}", [ReportSalesController::class, 'show']);
    });


    // Logout
    Route::get("logout", [AuthController::class, 'logout']);

    // Ajax Request
    Route::match(['get', 'post'], "products/search", [ProductController::class, '_ajax_getProducts']);
    Route::post("product/get-stock", [ProductController::class, '__ajax__getProductStock']);
    Route::match(['get', 'post'], "product-category/search", [ProductCategoryController::class, '__ajax__searchProduct']);
});
