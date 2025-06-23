<?php

namespace App\Http\Controllers\Admin\klasifikasiInstansi;

use App\Models\Bidang;
use App\Models\Unit;
use App\Models\Setting;
use App\Models\SubUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SubUnitController extends Controller
{
    public function index(Request $request)
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Sub Unit',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => true, 'url' => '#', 'title' => 'Sub Unit'],
        ];

        if ($request->ajax()) {
            $data = SubUnit::with('unit.bidang')->get();
            return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($row) {
                $actionBtn = '<div class="dropdown">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog fa-fw"></i> Aksi</button>
                    <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="admin/subunit/' . $row->id . '/edit">Ubah</a></li>
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li></ul></div>';
                return $actionBtn;
            })->addColumn('bidang', function ($row) {
                return ($row->unit && $row->unit->bidang) ? $row->unit->bidang->kode_bidang . ' - ' . $row->unit->bidang->nama_bidang : '-';
            })->addColumn('unit', function ($row) {
                return $row->unit ? $row->unit->kode_unit . ' - ' . $row->unit->nama_unit : '-';
            })->rawColumns(['action'])->make(true);
        }
        return view('admin.subunit.index', compact('config', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Tambah Sub Unit',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/subunit', 'title' => 'Sub Unit'],
            ['disabled' => true, 'url' => '#', 'title' => 'Tambah'],
        ];
        $bidangs = Bidang::all();
        $units = Unit::with('bidang')->get();
        return view('admin.subunit.form', compact('config', 'breadcrumbs', 'units', 'bidangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bidang_id' => 'required|exists:bidangs,id',
            'unit_id' => 'required|exists:units,id',
            'kode_sub_unit' => 'required|max:255|unique:sub_units,kode_sub_unit',
            'nama_sub_unit' => 'required|max:255',
            'kode' => 'required',
        ], [
            'bidang_id.required' => 'Bidang harus dipilih',
            'bidang_id.exists' => 'Bidang tidak valid',
            'unit_id.required' => 'unit harus dipilih',
            'unit_id.exists' => 'unit tidak valid',
            'kode_sub_unit.required' => 'Kode sub unit harus diisi',
            'kode_sub_unit.unique' => 'Kode sub unit sudah terdaftar',
            'nama_sub_unit.required' => 'Nama sub unit harus diisi',
        ]);

        if (!$validator->fails()) {
            $data = $validator->safe()->all();
            DB::beginTransaction();
            try {
                SubUnit::create($data);
                DB::commit();
                $response = response()->json(['message' => 'Data has been save', 'redirect' => 'admin/subunit']);
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
        $data = SubUnit::findOrFail($id);
        $bidangs = Bidang::all();
        $units = Unit::with('bidang')->get();

        $setting = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $setting[0]->value . ' - Edit Sub Unit',
            'description' => $setting[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/subunit', 'title' => 'Sub Unit'],
            ['disabled' => true, 'url' => '#', 'title' => 'Edit'],
        ];

        return view('admin.subunit.form', compact('config', 'breadcrumbs', 'data', 'units', 'bidangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bidang_id' => 'required|exists:bidangs,id',
            'unit_id' => 'required|exists:units,id',
            'kode_sub_unit' => 'required|max:255|unique:sub_units,kode_sub_unit,' . $id,
            'nama_sub_unit' => 'required|max:255',
            'kode' => 'required',
        ], [
            'bidang_id.required' => 'Bidang harus dipilih',
            'bidang_id.exists' => 'Bidang tidak valid',
            'unit_id.required' => 'unit harus dipilih',
            'unit_id.exists' => 'unit tidak valid',
            'kode_sub_unit.required' => 'Kode sub_unit harus diisi',
            'kode_sub_unit.unique' => 'Kode sub_unit sudah terdaftar',
            'nama_sub_unit.required' => 'Nama sub_unit harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $sub_unit = SubUnit::findOrFail($id);
        $sub_unit->update($validator->validated());

        return response()->json([
            'message' => 'Data berhasil diupdate',
            'redirect' => url('admin/subunit')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = SubUnit::findOrFail($id);
        if ($data->delete()) {
            $response = response()->json(['status' => 'success', 'message' => 'Data has been delete']);
        } else {
            $response = response()->json(['status' => 'failed', 'message' => 'Data cant delete']);
        }
        return $response;
    }
}
