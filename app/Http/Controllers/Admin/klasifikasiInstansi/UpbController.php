<?php

namespace App\Http\Controllers\Admin\klasifikasiInstansi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KlasifikasiInstansi\UpbResource;
use App\Models\klasifikasiInstansi\Upb;
use Illuminate\Support\Facades\Validator;

class UpbController extends Controller
{
    public function index(Request $request)
    {
        $upb = Upb::with('sub_unit.unit.bidang.kabupaten_kota.provinsi')->paginate(10);
        return UpbResource::collection($upb);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sub_unit_id' => 'required|exists:sub_units,id',
            'kode_upb' => 'required|max:255|unique:upbs,kode_upb',
            'nama_upb' => 'required|max:255|unique:upbs,kode_upb',
            'kode' => 'required',
        ], [
            'sub_unit_id.required' => 'sub_unit harus dipilih',
            'sub_unit_id.exists' => 'sub_unit tidak valid',
            'kode_upb.required' => 'Kode upb harus diisi',
            'kode_upb.unique' => 'Kode upb sudah terdaftar',
            'nama_upb.required' => 'Nama upb harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $upb = Upb::create($validator->validated());
        $upb->load('sub_unit.unit.bidang.kabupaten_kota.provinsi');
        return (new UpbResource($upb))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $upb = Upb::with('sub_unit.unit.bidang.kabupaten_kota.provinsi')->find($id);
        if (!$upb) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return new UpbResource($upb);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sub_unit_id' => 'required|exists:sub_units,id',
            'kode_upb' => 'required|max:255|unique:upbs,kode_upb,' . $id,
            'nama_upb' => 'required|max:255|unique:upbs,kode_upb',
            'kode' => 'required',
        ], [
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
        $upb->load('sub_unit.unit.bidang.kabupaten_kota.provinsi');
        return new UpbResource($upb);
    }

    public function destroy($id)
    {
        $upb = Upb::findOrFail($id);
        $upb->delete();
        return response()->json(null, 204);
    }
}