<?php

namespace App\Http\Controllers\Admin\klasifikasiInstansi;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\klasifikasiInstansi\Unit;
use App\Models\klasifikasiInstansi\Bidang;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Unit',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => true, 'url' => '#', 'title' => 'Unit'],
        ];

        if ($request->ajax()) {
            $data = Unit::with('bidang')->get();
            return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($row) {
                $actionBtn = '<div class="dropdown">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog fa-fw"></i> Aksi</button>
                    <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="admin/unit/' . $row->id . '/edit">Ubah</a></li>
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li></ul></div>';
                return $actionBtn;
            })->addColumn('bidang', function ($row) {
                return $row->bidang ? $row->bidang->kode_bidang . ' - ' . $row->bidang->nama_bidang : '-';
            })->rawColumns(['action'])->make(true);
        }
        return view('admin.unit.index', compact('config', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Tambah Unit',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/unit', 'title' => 'Unit'],
            ['disabled' => true, 'url' => '#', 'title' => 'Tambah'],
        ];
        $bidangs = Bidang::all();
        return view('admin.unit.form', compact('config', 'breadcrumbs', 'bidangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bidang_id' => 'required|exists:bidangs,id',
            'kode_unit' => 'required|max:255|unique:units,kode_unit',
            'nama_unit' => 'required|max:255',
            'kode' => 'required',
        ], [
            'bidang_id.required' => 'Bidang harus dipilih',
            'bidang_id.exists' => 'Bidang tidak valid',
            'kode_unit.required' => 'Kode unit harus diisi',
            'kode_unit.unique' => 'Kode unit sudah terdaftar',
            'nama_unit.required' => 'Nama unit harus diisi',
        ]);

        if (!$validator->fails()) {
            $data = $validator->safe()->all();
            DB::beginTransaction();
            try {
                Unit::create($data);
                DB::commit();
                $response = response()->json(['message' => 'Data has been save', 'redirect' => 'admin/unit']);
            } catch (\Throwable $throw) {
                DB::rollBack();
                Log::error($throw);
                $response = response()->json(['error' => $throw->getMessage()]);
            }
        } else {
            $response = response()->json(['error' => $validator->errors()]);
        }
        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Logic to show a specific unit
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Unit::findOrFail($id);
        $bidangs = Bidang::all();

        $setting = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $setting[0]->value . ' - Edit Unit',
            'description' => $setting[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/unit', 'title' => 'Unit'],
            ['disabled' => true, 'url' => '#', 'title' => 'Edit'],
        ];

        return view('admin.unit.form', compact('config', 'breadcrumbs', 'data', 'bidangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bidang_id' => 'required|exists:bidangs,id',
            'kode_unit' => 'required|max:255|unique:units,kode_unit,' . $id,
            'nama_unit' => 'required',
            'kode' => 'required',
        ], [
            'bidang_id.required' => 'Bidang harus dipilih',
            'bidang_id.exists' => 'Bidang tidak valid',
            'kode_unit.required' => 'Kode unit harus diisi',
            'kode_unit.unique' => 'Kode unit sudah terdaftar',
            'nama_unit.required' => 'Nama unit harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $unit = Unit::findOrFail($id);
        $unit->update($validator->validated());

        return response()->json([
            'message' => 'Data berhasil diupdate',
            'redirect' => url('admin/unit')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Unit::findOrFail($id);
        if ($data->delete()) {
            $response = response()->json(['status' => 'success', 'message' => 'Data has been delete']);
        } else {
            $response = response()->json(['status' => 'failed', 'message' => 'Data cant delete']);
        }
        return $response;
    }
}
