<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = Product::query()
                ->with(['category', 'unit'])
                ->whereHas("category", function ($query) {
                    $query->where("deleted_at", null);
                })
                ->whereHas("unit", function ($query) {
                    $query->where("deleted_at", null);
                });

            $index = 0;

            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->editColumn("selling_price", function ($model) {
                    return "Rp " . number_format($model->selling_price, 0, ',', '.');
                })
                ->editColumn("stock_limit", function ($model) {
                    return number_format($model->stock_limit, 0, ',', '.');
                })
                ->addColumn('action', function ($model) use (&$index) {
                    $index++;
                    $action_el = '
                    <div class="hstack gap-2 fs-15">
                        <form action="" class="m-0" method="post">
                        ' . csrf_field() . '
                            <a href="' . route("product.edit", $model->id) . '"
                                class="btn btn-icon btn-sm btn-warning">
                                <i class="ri-pencil-line"></i>
                            </a>
                        </form>
                        <form action="' . route("product.destroy", $model->id) . '" class="m-0 form-delete-' . $index . '" method="post">
                            ' . csrf_field() . '
                            ' . method_field("DELETE") . '
                            <input type="hidden" name="name" value="' . $model->name . '">

                            <a href="#"
                                class="btn btn-icon btn-sm btn-danger btn-delete" data-id="' . $index . '" data-name="' . $model->name . '">
                                <i class="ri-delete-bin-line"></i>
                            </a>
                        </form>
                    </div>
                    ';

                    return $action_el;
                })
                ->toJson();
        }
        return view("pages.product.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = ProductCategory::get();
        $unit = Unit::get();

        return view("pages.product.create", [
            'categories' => $category,
            'units' => $unit
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barcode' => "required|unique:products,barcode",
            'name' => "required",
            "category_id" => "required",
            "unit_id" => "required",
            "selling_price" => "required",
            "stock_limit" => "required",
        ], [
            'barcode.required' => "Barcode wajib di isi",
            "barcode.unique" => "Barcode sudah di gunakan",
            "name.required" => "Nama produk wajib di isi",
            "category_id.required" => "Kategori wajib di isi",
            "unit_id.required" => "Satuan wajib di isi",
            "selling_price.required" => "Harga jual wajib di isi",
            "stock_limit.required" => "Stok limit wajib di isi"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        try {
            DB::beginTransaction();

            Product::create([
                'barcode' => $request->barcode,
                'name' => $request->name,
                'category_id' => $request->category_id,
                'unit_id' => $request->unit_id,
                'selling_price' => $request->selling_price,
                'stock_limit' => $request->stock_limit,
            ]);

            DB::commit();

            return redirect()->route("product.index")
                ->with("success", "Sukses")
                ->with("success_message", "Produk berhasil di tambahkan");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->withErrors($validator)
                ->with("error", "Gagal")
                ->with("error_message", "Terjadi kesalahan saat menambahkan produk silahkan coba lagi");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if ($id == null) {
            return redirect()->back();
        }

        try {
            $category = ProductCategory::get();
            $unit = Unit::get();
            $product = Product::where("id", $id)
                ->with(['category', 'unit'])->first();

            return view("pages.product.edit", [
                'product' =>  $product,
                'categories' => $category,
                'units' => $unit
            ]);
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($id == null) {
            return redirect()->back();
        }
        $validator = Validator::make($request->all(), [
            'barcode' => "required",
            'name' => "required",
            "category_id" => "required",
            "unit_id" => "required",
            "selling_price" => "required",
            "stock_limit" => "required",
        ], [
            'barcode.required' => "Barcode wajib di isi",
            "name.required" => "Nama produk wajib di isi",
            "category_id.required" => "Kategori wajib di isi",
            "unit_id.required" => "Satuan wajib di isi",
            "selling_price.required" => "Harga jual wajib di isi",
            "stock_limit.required" => "Stok limit wajib di isi"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        try {
            $check_barcode = Product::where("barcode", $request->barcode)
                ->where("id", "!=", $id)
                ->first();

            if ($check_barcode != null) {
                return redirect()->back()->withInput()
                    ->with("error", "Gagal mengupdate produk")
                    ->with("error_message", "Barcode sudah di gunakan.");
            }

            DB::beginTransaction();

            Product::where("id", $id)
                ->update([
                    'barcode' => $request->barcode,
                    'name' => $request->name,
                    'category_id' => $request->category_id,
                    'unit_id' => $request->unit_id,
                    'selling_price' => $request->selling_price,
                    'stock_limit' => $request->stock_limit
                ]);

            DB::commit();

            return redirect()->route("product.index")
                ->with("success", "Sukses")
                ->with("success_message", "Produk berhasil di update");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->withErrors($validator)
                ->with("error", "Gagal")
                ->with("error_message", "Terjadi kesalahan saat mengupdate produk silahkan coba lagi");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            Product::where("id", $id)
                ->delete();

            DB::commit();

            return redirect()->route("product.index")
                ->with("success", "Sukses")
                ->with("success_message", "Kategori " . $request->name  . " berhasil di hapus");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with("error", "Gagal")
                ->with("error_message", "Terjadi kesalahan saat menghapus produk " . $request->name . " silahkan coba lagi");
        }
    }

    // ---------AJAX----------

    public function _ajax_getProducts(Request $request)
    {
        $products = Product::where("name", "LIKE", '%' . $request->q . '%')
            ->orWhere("barcode", 'LIKE', '%' . $request->q . '%')
            ->get();

        return response()->json($products);
    }

    public function  __ajax__getProductStock()
    {
        $stock = Stock::where("product_id", request()->product_id)
            ->orderBy("created_at", "DESC")
            ->first();

        return response()->json(['data' => $stock]);
    }
}
