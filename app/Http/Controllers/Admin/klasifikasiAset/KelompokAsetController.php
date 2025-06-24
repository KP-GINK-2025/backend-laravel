<?php

namespace App\Http\Controllers\Admin\klasifikasiAset;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\klasifikasiAset\AkunAset;
use App\Models\klasifikasiAset\KelompokAset;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class KelompokAsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Kelompok Aset',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => true, 'url' => '#', 'title' => 'Kelompok Aset'],
        ];

        if ($request->ajax()) {
            $data = KelompokAset::with('akun_aset')->get();
            return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($row) {
                $actionBtn = '<div class="dropdown">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog fa-fw"></i> Aksi</button>
                    <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="admin/kelompokaset/' . $row->id . '/edit">Ubah</a></li>
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li></ul></div>';
                return $actionBtn;
            })->addColumn('akun_aset', function ($row) {
                return $row->akun_aset ? $row->akun_aset->kode_akun_aset . ' - ' . $row->akun_aset->nama_akun_aset : '-';
            })->rawColumns(['action'])->make(true);
        }
        return view('admin.kelompokaset.index', compact('config', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Tambah Kelompok Aset',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/kelompokaset', 'title' => 'Kelompok Aset'],
            ['disabled' => true, 'url' => '#', 'title' => 'Tambah'],
        ];
        $akunasets = AkunAset::all();
        return view('admin.kelompokaset.form', compact('config', 'breadcrumbs', 'akunasets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'akun_aset_id' => 'required|exists:akun_asets,id',
            'kode_kelompok_aset' => 'required|max:255|unique:kelompok_asets,kode_kelompok_aset',
            'nama_kelompok_aset' => 'required|max:255',
            'kode' => 'required',
        ], [
            'akun_aset_id.required' => 'akun_aset harus dipilih',
            'akun_aset_id.exists' => 'akun_aset tidak valid',
            'kode_kelompok_aset.required' => 'Kode kelompok_aset harus diisi',
            'kode_kelompok_aset.unique' => 'Kode kelompok_aset sudah terdaftar',
            'nama_kelompok_aset.required' => 'Nama kelompok_aset harus diisi',
        ]);

        if (!$validator->fails()) {
            $data = $validator->safe()->all();
            DB::beginTransaction();
            try {
                KelompokAset::create($data);
                DB::commit();
                $response = response()->json(['message' => 'Data has been save', 'redirect' => 'admin/kelompokaset']);
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
        $data = KelompokAset::findOrFail($id);
        $akunasets = AkunAset::all();

        $setting = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $setting[0]->value . ' - Edit Kelompok Aset',
            'description' => $setting[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/kelompokaset', 'title' => 'Kelompok Aset'],
            ['disabled' => true, 'url' => '#', 'title' => 'Edit'],
        ];

        return view('admin.kelompokaset.form', compact('config', 'breadcrumbs', 'data', 'akunasets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'akun_aset_id' => 'required|exists:akun_asets,id',
            'kode_kelompok_aset' => 'required|max:255|unique:kelompok_asets,kode_kelompok_aset,' . $id,
            'nama_kelompok_aset' => 'required',
            'kode' => 'required',
        ], [
            'akun_aset_id.required' => 'akun_aset harus dipilih',
            'akun_aset_id.exists' => 'akun_aset tidak valid',
            'kode_kelompok_aset.required' => 'Kode kelompok_aset harus diisi',
            'kode_kelompok_aset.unique' => 'Kode kelompok_aset sudah terdaftar',
            'nama_kelompok_aset.required' => 'Nama kelompok_aset harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $kelompokaset = KelompokAset::findOrFail($id);
        $kelompokaset->update($validator->validated());

        return response()->json([
            'message' => 'Data berhasil diupdate',
            'redirect' => url('admin/kelompokaset')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = KelompokAset::findOrFail($id);
        if ($data->delete()) {
            $response = response()->json(['status' => 'success', 'message' => 'Data has been delete']);
        } else {
            $response = response()->json(['status' => 'failed', 'message' => 'Data cant delete']);
        }
        return $response;
    }
}
