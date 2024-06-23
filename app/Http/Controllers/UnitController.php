<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = Unit::query();
            $index = 0;

            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('action', function ($model) use (&$index) {
                    $index++;
                    $action_el = '
                    <div class="hstack gap-2 fs-15">
                        <form action="" class="m-0" method="post">
                        ' . csrf_field() . '
                            <a href="' . route("unit.edit", $model->id) . '"
                                class="btn btn-icon btn-sm btn-warning">
                                <i class="ri-pencil-line"></i>
                            </a>
                        </form>
                        <form action="' . route("unit.destroy", $model->id) . '" class="m-0 form-delete-' . $index . '" method="post">
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
        return view("pages.units.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.units.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => "required|unique:category_products,name"
        ], [
            'name.unique' => "Nama unit sudah di gunakan",
            'name.required' => "Nama unit wajib di isi"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        try {
            DB::beginTransaction();

            Unit::create([
                'name' => $request->name
            ]);

            DB::commit();

            return redirect()->route("unit.index")
                ->with("success", "Sukses")
                ->with("success_message", "Unit berhasil di tambahkan");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with("error", "Gagal")
                ->with("error_message", "Terjadi kesalahan saat menambahkan unit silahkan coba lagi");
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
            $unit = Unit::find($id);

            return view("pages.units.edit", [
                'unit' => $unit
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
        ], [
            'name.required' => "Nama unit wajib di isi"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        try {
            DB::beginTransaction();

            Unit::where("id", $id)
                ->update([
                    'name' => $request->name
                ]);

            DB::commit();

            return redirect()->route("unit.index")
                ->with("success", "Sukses")
                ->with("success_message", "Unit produk berhasil di update");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->withErrors($validator)
                ->with("error", "Gagal")
                ->with("error_message", "Terjadi kesalahan saat mengupdate unit produk silahkan coba lagi");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            Unit::where("id", $id)
                ->delete();

            DB::commit();

            return redirect()->route("unit.index")
                ->with("success", "Sukses")
                ->with("success_message", "Unit " . $request->name  . " berhasil di hapus");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with("error", "Gagal")
                ->with("error_message", "Terjadi kesalahan saat menghapus unit " . $request->name . " silahkan coba lagi");
        }
    }
}
