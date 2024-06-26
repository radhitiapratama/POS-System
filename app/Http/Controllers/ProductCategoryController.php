<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = ProductCategory::query();
            $index = 0;

            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('action', function ($model) use (&$index) {
                    $index++;
                    $action_el = '
                    <div class="hstack gap-2 fs-15">
                        <form action="" class="m-0" method="post">
                        ' . csrf_field() . '
                            <a href="' . route("product-category.edit", $model->id) . '"
                                class="btn btn-icon btn-sm btn-warning">
                                <i class="ri-pencil-line"></i>
                            </a>
                        </form>
                        <form action="' . route("product-category.destroy", $model->id) . '" class="m-0 form-delete-' . $index . '" method="post">
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
        return view("pages.product-category.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.product-category.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => "required|unique:category_products,name"
        ], [
            'name.unique' => "Nama Produk sudah di gunakan",
            'name.required' => "Nama Produk wajib di isi"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        try {
            DB::beginTransaction();

            ProductCategory::create([
                'name' => $request->name,
            ]);

            DB::commit();

            return redirect()->route("product-category.index")
                ->with("success", "Sukses")
                ->with("success_message", "Kategori produk berhasil di tambahkan");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->withErrors($validator)
                ->with("error", "Gagal")
                ->with("error_message", "Terjadi kesalahan saat menambahkan kategori produk silahkan coba lagi");
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
            $category = ProductCategory::find($id);

            return view("pages.product-category.edit", [
                'category' => $category
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
            'name' => "required"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        try {
            DB::beginTransaction();

            ProductCategory::where("id", $id)
                ->update([
                    'name' => $request->name
                ]);

            DB::commit();

            return redirect()->route("product-category.index")
                ->with("success", "Sukses")
                ->with("success_message", "Kategori produk berhasil di update");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->withErrors($validator)
                ->with("error", "Gagal")
                ->with("error_message", "Terjadi kesalahan saat mengupdate kategori produk silahkan coba lagi");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            ProductCategory::where("id", $id)
                ->delete();

            DB::commit();

            return redirect()->route("product-category.index")
                ->with("success", "Sukses")
                ->with("success_message", "Kategori " . $request->name  . " berhasil di hapus");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with("error", "Gagal")
                ->with("error_message", "Terjadi kesalahan saat menghapus kategori " . $request->name . " silahkan coba lagi");
        }
    }

    public function __ajax__searchProduct(Request $request)
    {
        $category = ProductCategory::where("name", 'LIKE', '%' . $request->q . "%")
            ->get();

        return response()->json($category);
    }
}
