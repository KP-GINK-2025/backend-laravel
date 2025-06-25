<?php

namespace App\Http\Controllers\Admin\klasifikasiInstansi;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\klasifikasiInstansi\Bidang;
use App\Models\klasifikasiInstansi\Provinsi;
use App\Models\klasifikasiInstansi\KabupatenKota;

class BidangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Bidang',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => true, 'url' => '#', 'title' => 'Bidang'],
        ];

        if ($request->ajax()) {
            $data = Bidang::with('kabupaten_kota.provinsi')->get();
            return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($row) {
                $actionBtn = '<div class="dropdown">
                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog fa-fw"></i> Aksi</button>
                <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="admin/bidang/' . $row->id . '/edit">Ubah</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li></ul></div>';
                return $actionBtn;
            })->addColumn('provinsi', function ($row) {
                return ($row->kabupaten_kota && $row->kabupaten_kota->provinsi) ? $row->kabupaten_kota->provinsi->kode_provinsi . ' - ' . $row->kabupaten_kota->provinsi->nama_provinsi : '-';
            })->addColumn('kabupaten_kota', function ($row) {
                return $row->kabupaten_kota ? $row->kabupaten_kota->kode_kabupaten_kota . ' - ' . $row->kabupaten_kota->nama_kabupaten_kota : '-';
            })->rawColumns(['action'])->make(true);
        }
        return view('admin.bidang.index', compact('config', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Tambah Bidang',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/bidang', 'title' => 'Bidang'],
            ['disabled' => true, 'url' => '#', 'title' => 'Tambah'],
        ];
        $provinsis = Provinsi::all();
        $kabupaten_kotas = KabupatenKota::with('provinsi')->get();
        return view('admin.bidang.form', compact('config', 'breadcrumbs', 'kabupaten_kotas', 'provinsis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provinsi_id' => 'required|exists:provinsis,id',
            'kabupaten_kota_id' => 'required|exists:kabupaten_kotas,id',
            'kode_bidang' => 'required|max:255|unique:bidangs,kode_bidang',
            'nama_bidang' => 'required|max:255',
            'kode' => 'required',
        ], [
            'kabupaten_kota_id.required' => 'kabupaten_kota harus dipilih',
            'kabupaten_kota_id.exists' => 'kabupaten_kota tidak valid',
            'provinsi_id.required' => 'provinsi harus dipilih',
            'provinsi_id.exists' => 'provinsi tidak valid',
            'kode_bidang' => ':attribute harus diisi',
            'kode_bidang.unique' => 'Kode bidang sudah terdaftar',
            'nama_bidang' => ':attribute harus diisi',
        ]);

        if (!$validator->fails()) {
            $data = $validator->safe()->all();
            DB::beginTransaction();
            try {
                Bidang::create($data);
                DB::commit();
                $response = response()->json(['message' => 'Data has been save', 'redirect' => 'admin/bidang']);
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
        // Logic to show a specific bidang
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Bidang::findOrFail($id);

        $setting = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $setting[0]->value . ' - Edit Bidang',
            'description' => $setting[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/bidang', 'title' => 'Bidang'],
            ['disabled' => true, 'url' => '#', 'title' => 'Edit'],
        ];
        $provinsis = Provinsi::all();
        $kabupaten_kotas = KabupatenKota::with('provinsi')->get();
        return view('admin.bidang.form', compact('config', 'breadcrumbs', 'data', 'provinsis', 'kabupaten_kotas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kabupaten_kota_id' => 'required|exists:kabupaten_kotas,id',
            'kode_bidang' => 'required|max:255|unique:bidangs,kode_bidang,' . $id,
            'nama_bidang' => 'required|max:255',
            'kode' => 'required',
        ], [
            'kabupaten_kota_id.required' => 'kabupaten_kota harus dipilih',
            'kabupaten_kota_id.exists' => 'kabupaten_kota tidak valid',
            'kode_bidang' => ':attribute harus diisi',
            'kode_bidang.unique' => 'Kode bidang sudah terdaftar',
            'nama_bidang' => ':attribute harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $bidang = Bidang::findOrFail($id);
        $bidang->update($validator->validated());

        return response()->json([
            'message' => 'Data berhasil diupdate',
            'redirect' => url('admin/bidang')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Bidang::findOrFail($id);
        if ($data->delete()) {
            $response = response()->json(['status' => 'success', 'message' => 'Data has been delete']);
        } else {
            $response = response()->json(['status' => 'failed', 'message' => 'Data cant delete']);
        }
        return $response;
    }
}