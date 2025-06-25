<?php

namespace App\Http\Controllers\Admin\klasifikasiAset;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\klasifikasiAset\JenisAset;
use App\Models\klasifikasiAset\AkunAset;
use App\Models\klasifikasiAset\KelompokAset;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class JenisAsetController extends Controller
{
    public function index(Request $request)
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Jenis Aset',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => true, 'url' => '#', 'title' => 'Jenis Aset'],
        ];

        if ($request->ajax()) {
            $data = JenisAset::with('kelompok_aset.akun_aset')->get();
            return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($row) {
                $actionBtn = '<div class="dropdown">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog fa-fw"></i> Aksi</button>
                    <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="admin/jenisaset/' . $row->id . '/edit">Ubah</a></li>
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li></ul></div>';
                return $actionBtn;
            })->addColumn('akun_aset', function ($row) {
                return ($row->kelompok_aset && $row->kelompok_aset->akun_aset) ? $row->kelompok_aset->akun_aset->kode_akun_aset . ' - ' . $row->kelompok_aset->akun_aset->nama_akun_aset : '-';
            })->addColumn('kelompok_aset', function ($row) {
                return $row->kelompok_aset ? $row->kelompok_aset->kode_kelompok_aset . ' - ' . $row->kelompok_aset->nama_kelompok_aset : '-';
            })->rawColumns(['action'])->make(true);
        }
        return view('admin.jenisaset.index', compact('config', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Tambah Jenis Aset',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/jenisaset', 'title' => 'Jenis Aset'],
            ['disabled' => true, 'url' => '#', 'title' => 'Tambah'],
        ];
        $akunasets = AkunAset::all();
        $kelompokasets = KelompokAset::with('akun_aset')->get();
        return view('admin.jenisaset.form', compact('config', 'breadcrumbs', 'kelompokasets', 'akunasets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'akun_aset_id' => 'required|exists:akun_asets,id',
            'kelompok_aset_id' => 'required|exists:kelompok_asets,id',
            'kode_jenis_aset' => 'required|max:255|unique:jenis_asets,kode_jenis_aset',
            'nama_jenis_aset' => 'required|max:255',
            'kode' => 'required',
        ], [
            'akun_aset_id.required' => 'akun_aset harus dipilih',
            'akun_aset_id.exists' => 'akun_aset tidak valid',
            'kelompok_aset_id.required' => 'kelompok_aset harus dipilih',
            'kelompok_aset_id.exists' => 'kelompok_aset tidak valid',
            'kode_jenis_aset.required' => 'Kode jenis aset harus diisi',
            'kode_jenis_aset.unique' => 'Kode jenis aset sudah terdaftar',
            'nama_jenis_aset.required' => 'Nama jenis aset harus diisi',
        ]);

        if (!$validator->fails()) {
            $data = $validator->safe()->all();
            DB::beginTransaction();
            try {
                JenisAset::create($data);
                DB::commit();
                $response = response()->json(['message' => 'Data has been save', 'redirect' => 'admin/jenisaset']);
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
        $data = JenisAset::findOrFail($id);
        $akunasets = AkunAset::all();
        $kelompokasets = KelompokAset::with('akunaset')->get();

        $setting = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $setting[0]->value . ' - Edit Jenis Aset',
            'description' => $setting[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/jenisaset', 'title' => 'Jenis Aset'],
            ['disabled' => true, 'url' => '#', 'title' => 'Edit'],
        ];

        return view('admin.jenisaset.form', compact('config', 'breadcrumbs', 'data', 'kelompokasets', 'akunasets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'akun_aset_id' => 'required|exists:akun_asets,id',
            'kelompok_aset_id' => 'required|exists:kelompok_asets,id',
            'kode_jenis_aset' => 'required|max:255|unique:jenis_asets,kode_jenis_aset,' . $id,
            'nama_jenis_aset' => 'required|max:255',
            'kode' => 'required',
        ], [
            'akun_aset_id.required' => 'akun_aset harus dipilih',
            'akun_aset_id.exists' => 'akun_aset tidak valid',
            'kelompok_aset_id.required' => 'kelompok_aset harus dipilih',
            'kelompok_aset_id.exists' => 'kelompok_aset tidak valid',
            'kode_jenis_aset.required' => 'Kode jenis_aset harus diisi',
            'kode_jenis_aset.unique' => 'Kode jenis_aset sudah terdaftar',
            'nama_jenis_aset.required' => 'Nama jenis_aset harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $jenis_aset = JenisAset::findOrFail($id);
        $jenis_aset->update($validator->validated());

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
        $data = JenisAset::findOrFail($id);
        if ($data->delete()) {
            $response = response()->json(['status' => 'success', 'message' => 'Data has been delete']);
        } else {
            $response = response()->json(['status' => 'failed', 'message' => 'Data cant delete']);
        }
        return $response;
    }
}
