<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\StockHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    protected $stock_types = [
        'add-stock',
        'additional-adjustment',
        'reduction-adjustment'
    ];

    public function index(Request $request)
    {
        $last_month = date("Y-m-t 23:59:59", strtotime("-1 month"));
        $month_now = date("Y-m-t 23:59:59");

        // $model = Product::query()
        //     ->with(['category',])
        //     ->whereHas("category", function ($q) {
        //         $q->whereNull("deleted_at");
        //     })
        //     ->addSelect([
        //         'initial_stock' => StockHistory::query()
        //             ->select("remaining_stock")
        //             ->whereColumn('product_id', 'products.id')
        //             ->where("created_at", "<=", $last_month)
        //             ->latest()
        //             ->take(1),
        //         'remaining_stock' => Stock::query()
        //             ->select("stock")
        //             ->whereColumn("product_id", "products.id")
        //             ->take(1)
        //     ])->orderBy("id", "DESC")->get()->take(1);

        if ($request->ajax()) {
            $index = 0;

            $search_value = $request->input("search.value");
            $filter_month = $request->filter_month;
            $date_initial_stock = date("Y-m-t 23:59:59", strtotime($filter_month . " -1 month"));
            $date_remaining_stock = date("Y-m-t 23:59:59", strtotime($filter_month));

            $model = Product::query()
                ->with(['category',])
                ->whereHas("category", function ($q) {
                    $q->whereNull("deleted_at");
                })
                ->addSelect([
                    'initial_stock' => StockHistory::query()
                        ->select("remaining_stock")
                        ->whereColumn('product_id', 'products.id')
                        ->when($filter_month == null, function ($q) use ($last_month) {
                            return $q->where("created_at", "<=", $last_month);
                        })
                        ->when($filter_month != null, function ($q) use ($date_initial_stock) {
                            return $q->where("created_at", "<=", $date_initial_stock);
                        })
                        ->orderBy('created_at', 'DESC')
                        ->take(1),
                    'remaining_stock' => Stock::query()
                        ->select("stock")
                        ->whereColumn("product_id", "products.id")
                        ->when($filter_month == null, function ($q) use ($month_now) {
                            return $q->where("created_at", "<=", $month_now);
                        })
                        ->when($filter_month != null, function ($q) use ($date_remaining_stock) {
                            return $q->where("created_at", "<=", $date_remaining_stock);
                        })
                        ->orderBy('created_at', 'DESC')
                        ->take(1)
                ]);

            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->filter(function ($q) use ($search_value, $filter_month) {
                    if ($search_value != null || $search_value != "") {
                        $q->where("products.name", "LIKE", '%' . $search_value . "%")
                            ->orWhereHas('category', function ($query) use ($search_value) {
                                $query->where('name', 'LIKE', '%' . $search_value . '%');
                            });
                    }
                })
                ->editColumn("initial_stock", function ($model) {
                    $stock = "-";
                    if ($model->initial_stock != null) {
                        $stock = $model->initial_stock;
                        return number_format($stock, 0, ",", ".");
                    }

                    return $stock;
                })
                ->editColumn("remaining_stock", function ($model) {
                    $stock = "-";
                    if ($model->remaining_stock != null) {
                        $stock = $model->remaining_stock;
                        return number_format($stock, 0, ",", ".");
                    }
                    return $stock;
                })
                ->addColumn('action', function ($model) use (&$index, $filter_month) {
                    $index++;
                    $action_el = '
                    <div class="hstack gap-2 fs-15 d-flex justify-content-center">
                        <a href="' . url("stock/$model->id/history?filter_month=" . $filter_month . "") . '"
                            class="btn btn-icon btn-sm btn-info">
                            <i class="ri-history-line"></i>
                        </a>
                    </div>
                    ';

                    return $action_el;
                })
                ->toJson();
        }

        return view("pages.stock.index");
    }

    public function create()
    {
        return view("pages.stock.add-stock");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => "required",
            "qty" => "required",
        ], [
            'product_id.required' => "Produk wajib di isi",
            "qty.required" => "Qty wajib di isi"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        try {
            DB::beginTransaction();

            $get_stock = Stock::where("product_id", $request->product_id)->orderBy("created_at", "DESC")->first();
            $stock = 0;

            if ($get_stock != null) {
                $stock = $get_stock->stock;
            }

            if ($get_stock == null) {
                Stock::create([
                    'product_id' => $request->product_id,
                    'stock' => $request->qty + $stock
                ]);
            } else {
                Stock::where("product_id", $request->product_id)
                    ->update([
                        'stock' => $request->qty + $stock,
                    ]);
            }

            StockHistory::create([
                'product_id' => $request->product_id,
                'type' => "add-stock",
                'qty' => $request->qty,
                'stock_before' => $stock,
                'remaining_stock' => $request->qty + $stock,
            ]);

            DB::commit();

            return redirect("stock")
                ->with("success", "Sukses")
                ->with("success_message", "Stok produk berhasil di tambahkan");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->withErrors($validator)
                ->with("error", "Gagal")
                ->with("error_message", "Terjadi kesalahan saat menambahkan stok produk silahkan coba lagi");
        }
    }

    public function stockAdjustment()
    {
        return view("pages.stock.stock-adjustment.add");
    }

    public function stockAdjustmentStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => "required",
            'type_adjustment' => "required",
            "qty" => "required",
        ], [
            'product_id.required' => "Produk wajib di isi",
            'type-adjustment.required' => "Tipe penyesuaian wajib di isi",
            "qty.required" => "Qty wajib di isi"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        try {
            $type = $request->type_adjustment;

            if ($type == "reduction-adjustment" && $request->qty > $request->current_product_stock) {
                return redirect()->back()->withInput()
                    ->with("error", "gagal")
                    ->with("error_message", "Qty penyesuaian tidak boleh lebih besar dari sisa stok produk saat ini!");
            }

            // validate stock adjustment type
            if (!in_array($type, $this->stock_types)) {
                return redirect()->back()->withInput()
                    ->with("error", "Gagal")
                    ->with("error_message", "Terjadi kesalahan saat menambahkan penyesuaian stok produk silahkan coba lagi");
            }

            DB::beginTransaction();

            $get_stock = Stock::where("product_id", $request->product_id)->orderBy("created_at", "DESC")->first();
            $stock = 0;

            if ($get_stock != null) {
                // cek type adjustment
                if ($type == "additional-adjustment") {
                    $stock = $get_stock->stock + $request->qty; // add
                } else {
                    $stock = $get_stock->stock - $request->qty; // min
                }
            }

            if ($get_stock == null) {
                Stock::create([
                    'product_id' => $request->product_id,
                    'stock' => $stock
                ]);
            } else {
                Stock::where("product_id", $request->product_id)
                    ->update([
                        'stock' => $stock
                    ]);
            }

            StockHistory::create([
                'product_id' => $request->product_id,
                'type' => $request->type_adjustment,
                'qty' => $request->qty,
                'stock_before' => $get_stock->stock,
                'remaining_stock' => $stock,
            ]);

            DB::commit();

            return redirect("stock")
                ->with("success", "Sukses")
                ->with("success_message", "Penyesuaian stok berhasil");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->withErrors($validator)
                ->with("error", "Gagal")
                ->with("error_message", "Terjadi kesalahan saat penyesuaian stok silahkan coba lagi");
        }
    }

    public function history(Request $request, $product_id)
    {
        if ($product_id == null) {
            return redirect()->back();
        }

        if ($request->ajax()) {
            $filter_month = $request->filter_month;
            $start_date = null;
            $end_date = null;

            $is_filter_date_range = false;
            $date_range_start = $request->filter_start_date;
            $date_range_end = $request->filter_end_date;
            if ($date_range_start != null && $date_range_end != null) {
                $is_filter_date_range = true;
                $date_range_start = $date_range_start . " 00:00:00";
                $date_range_end = $date_range_end . " 23:59:59";
            }

            if ($filter_month != null) {
                $start_date = date("Y-m-01 00:00:00", strtotime($filter_month));
                $end_date = date("Y-m-t 23:59:59", strtotime($filter_month));
            }

            $model = StockHistory::query()
                ->with("product")
                ->where("product_id", $product_id)
                ->whereHas("product", function ($q) {
                    $q->whereNull("deleted_at");
                })
                ->when($filter_month != null && $is_filter_date_range == false, function ($q) use ($start_date, $end_date) {
                    return $q->where("created_at", ">=", $start_date)
                        ->where("created_at", "<=", $end_date);
                })
                ->when($is_filter_date_range == true, function ($q) use ($date_range_start, $date_range_end) {
                    return $q->where("created_at", ">=", $date_range_start)
                        ->where("created_at", "<=", $date_range_end);
                });

            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->editColumn("type", function ($model) {
                    if ($model->type == "add-stock") {
                        return '<span class="badge bg-info p-2">Penambahan Stok</span>';
                    } elseif ($model->type == "additional-adjustment") {
                        return '<span class="badge bg-primary p-2">Penyesuaian Penambahan</span>';
                    } elseif ($model->type == "reduction-adjustment") {
                        return '<span class="badge bg-warning p-2">Penyesuaian Pengurangan</span>';
                    } else {
                        return '<span class="badge bg-danger p-2">Penjualan</span>';
                    }
                })
                ->addColumn("created_at_formatted", function ($model) {
                    return Carbon::parse($model->created_at)->format("d-m-Y H:i:s");
                })
                ->rawColumns(["type"]) //supaya return data nya bisa html
                ->toJson();
        }

        return view("pages.stock.history");
    }
}
