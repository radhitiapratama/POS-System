<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::middleware("guest")->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name("login");
    Route::post("/login", [AuthController::class, 'handleLogin']);
});

Route::middleware("auth")->group(function () {
    Route::resource("product-category", ProductCategoryController::class)->except("show");
    Route::resource("unit", UnitController::class)->except("show");
    Route::resource("product", ProductController::class)->except("show");

    Route::prefix('stock')->group(function () {
        Route::get("/", [StockController::class, 'index']);
        Route::get("create", [StockController::class, 'create']);
        Route::post("store", [StockController::class, 'store']);
        Route::get('stock-adjustment', [StockController::class, 'stockAdjustment']);
        Route::post("stock-adjustment/store", [StockController::class, 'stockAdjustmentStore']);
        Route::get("{product_id}/history", [StockController::class, 'history']);
    });

    Route::get("cashier", [CashierController::class, 'index']);
    Route::post("cashier/product", [CashierController::class, '__ajax__getProduct']);
    Route::post("cashier/pay", [CashierController::class, 'pay']);

    Route::get("sales/{sale_id}/print", [SalesController::class, 'print']);

    // Ajax Request
    Route::get("products/get", [ProductController::class, '_ajax_getProducts']);
    Route::post("product/get-stock", [ProductController::class, '__ajax__getProductStock']);
});
