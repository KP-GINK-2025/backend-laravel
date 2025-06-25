<?php

namespace App\Http\Controllers\Admin\klasifikasiInstansi;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\klasifikasiInstansi\Upb;
use App\Models\klasifikasiInstansi\Unit;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\klasifikasiInstansi\Bidang;
use App\Models\klasifikasiInstansi\SubUnit;
use App\Models\klasifikasiInstansi\Provinsi;
use App\Models\klasifikasiInstansi\KabupatenKota;

class UpbController extends Controller
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
            ['disabled' => true, 'url' => '#', 'title' => 'UPB'],
        ];

        if ($request->ajax()) {
            $data = Upb::with('sub_unit.unit.bidang.kabupaten_kota.provinsi')->get();
            return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($row) {
                $actionBtn = '<div class="dropdown">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog fa-fw"></i> Aksi</button>
                    <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="admin/upb/' . $row->id . '/edit">Ubah</a></li>
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li></ul></div>';
                return $actionBtn;
            })->addColumn('provinsi', function ($row) {
                return ($row->sub_unit && $row->sub_unit->unit && $row->sub_unit->unit->bidang && $row->sub_unit->unit->bidang->kabupaten_kota && $row->sub_unit->unit->bidang->kabupaten_kota->provinsi)
                    ? $row->sub_unit->unit->bidang->kabupaten_kota->provinsi->kode_provinsi . ' - ' . $row->sub_unit->unit->bidang->kabupaten_kota->provinsi->nama_provinsi
                    : '-';
            })->addColumn('kabupaten_kota', function ($row) {
                return ($row->sub_unit && $row->sub_unit->unit && $row->sub_unit->unit->bidang && $row->sub_unit->unit->bidang->kabupaten_kota)
                    ? $row->sub_unit->unit->bidang->kabupaten_kota->kode_kabupaten_kota . ' - ' . $row->sub_unit->unit->bidang->kabupaten_kota->nama_kabupaten_kota
                    : '-';
            })->addColumn('bidang', function ($row) {
                return ($row->sub_unit && $row->sub_unit->unit && $row->sub_unit->unit->bidang)
                    ? $row->sub_unit->unit->bidang->kode_bidang . ' - ' . $row->sub_unit->unit->bidang->nama_bidang : '-';
            })->addColumn('unit', function ($row) {
                return ($row->sub_unit && $row->sub_unit->unit)
                    ? $row->sub_unit->unit->kode_unit . ' - ' . $row->sub_unit->unit->nama_unit : '-';
            })->addColumn('sub_unit', function ($row) {
                return $row->sub_unit
                    ? $row->sub_unit->kode_sub_unit . ' - ' . $row->sub_unit->nama_sub_unit : '-';
            })->rawColumns(['action'])->make(true);
        }
        return view('admin.upb.index', compact('config', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Tambah UPB',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/upb', 'title' => 'UPB'],
            ['disabled' => true, 'url' => '#', 'title' => 'Tambah'],
        ];
        $provinsis = Provinsi::all();
        $kabupaten_kotas = KabupatenKota::with('provinsi')->get();
        $bidangs = Bidang::with('kabupaten_kota')->get();
        $units = Unit::with(relations: 'bidang')->get();
        $sub_units = SubUnit::with('unit')->get();
        return view('admin.upb.form', compact('config', 'breadcrumbs', 'sub_units', 'units', 'bidangs', 'kabupaten_kotas', 'provinsis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provinsi_id' => 'required|exists:provinsis,id',
            'kabupaten_kota_id' => 'required|exists:kabupaten_kotas,id',
            'bidang_id' => 'required|exists:bidangs,id',
            'unit_id' => 'required|exists:units,id',
            'sub_unit_id' => 'required|exists:sub_units,id',
            'kode_upb' => 'required|max:255|unique:upbs,kode_upb',
            'nama_upb' => 'required|max:255',
            'kode' => 'required',
        ], [
            'provinsi_id.required' => 'provinsi harus dipilih',
            'provinsi_id.exists' => 'provinsi tidak valid',
            'kabupaten_kota_id.required' => 'kabupaten_kota harus dipilih',
            'kabupaten_kota_id.exists' => 'kabupaten_kota tidak valid',
            'bidang_id.required' => 'Bidang harus dipilih',
            'bidang_id.exists' => 'Bidang tidak valid',
            'unit_id.required' => 'unit harus dipilih',
            'unit_id.exists' => 'unit tidak valid',
            'sub_unit_id.required' => 'sub_unit harus dipilih',
            'sub_unit_id.exists' => 'sub_unit tidak valid',
            'kode_upb.required' => 'Kode upb harus diisi',
            'kode_upb.unique' => 'Kode upb sudah terdaftar',
            'nama_upb.required' => 'Nama upb harus diisi',
        ]);

        if (!$validator->fails()) {
            $data = $validator->safe()->all();
            DB::beginTransaction();
            try {
                Upb::create($data);
                DB::commit();
                $response = response()->json(['message' => 'Data has been save', 'redirect' => 'admin/upb']);
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
        $provinsis = Provinsi::all();
        $kabupaten_kotas = KabupatenKota::with('provinsi')->get();
        $bidangs = Bidang::with('kabupaten_kota')->get();
        $units = Unit::with(relations: 'bidang')->get();
        $sub_units = SubUnit::with('unit')->get();

        $setting = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $setting[0]->value . ' - Edit UPB',
            'description' => $setting[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/upb', 'title' => 'UPB'],
            ['disabled' => true, 'url' => '#', 'title' => 'Edit'],
        ];

        return view('admin.upb.form', compact('config', 'breadcrumbs', 'data', 'sub_units', 'units', 'bidangs', 'kabupaten_kotas', 'provinsis'))
            ->with('data', Upb::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'provinsi_id' => 'required|exists:provinsis,id',
            'kabupaten_kota_id' => 'required|exists:kabupaten_kotas,id',
            'bidang_id' => 'required|exists:bidangs,id',
            'unit_id' => 'required|exists:units,id',
            'sub_unit_id' => 'required|exists:sub_units,id',
            'kode_upb' => 'required|max:255|unique:upbs,kode_upb,' . $id,
            'nama_upb' => 'required|max:255',
            'kode' => 'required',
        ], [
            'provinsi_id.required' => 'provinsi harus dipilih',
            'provinsi_id.exists' => 'provinsi tidak valid',
            'kabupaten_kota_id.required' => 'kabupaten_kota harus dipilih',
            'kabupaten_kota_id.exists' => 'kabupaten_kota tidak valid',
            'bidang_id.required' => 'Bidang harus dipilih',
            'bidang_id.exists' => 'Bidang tidak valid',
            'unit_id.required' => 'unit harus dipilih',
            'unit_id.exists' => 'unit tidak valid',
            'sub_unit_id.required' => 'sub_unit harus dipilih',
            'sub_unit_id.exists' => 'sub_unit tidak valid',
            'kode_upb.required' => 'Kode upb harus diisi',
            'kode_upb.unique' => 'Kode upb sudah terdaftar',
            'nama_upb.required' => 'Nama upb harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $upb = Upb::findOrFail($id);
        $upb->update($validator->validated());

        return response()->json([
            'message' => 'Data berhasil diupdate',
            'redirect' => url('admin/upb')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Upb::findOrFail($id);
        if ($data->delete()) {
            $response = response()->json(['status' => 'success', 'message' => 'Data has been delete']);
        } else {
            $response = response()->json(['status' => 'failed', 'message' => 'Data cant delete']);
        }
        return $response;
    }
}
