<?php

namespace App\Http\Controllers\Admin\klasifikasiInstansi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\klasifikasiInstansi\Bidang;
use App\Http\Resources\KlasifikasiInstansi\BidangResource;

class BidangController extends Controller
{
    public function index(Request $request)
    {
        $bidangs = Bidang::with('kabupaten_kota.provinsi')->paginate(10);
        return BidangResource::collection($bidangs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kabupaten_kota_id' => 'required|exists:kabupaten_kotas,id',
            'kode_bidang' => 'required|max:255|unique:bidangs,kode_bidang',
            'nama_bidang' => 'required|max:255|unique:bidangs,kode_bidang',
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

        $bidang = Bidang::create($validator->validated());
        $bidang->load('kabupaten_kota.provinsi');
        return (new BidangResource($bidang))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $bidang = Bidang::with('kabupaten_kota.provinsi')->find($id);
        if (!$bidang) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return new BidangResource($bidang);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kabupaten_kota_id' => 'required|exists:kabupaten_kotas,id',
            'kode_bidang' => 'required|max:255|unique:bidangs,kode_bidang,' . $id,
            'nama_bidang' => 'required|max:255|unique:bidangs,kode_bidang',
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
        $bidang->load('kabupaten_kota.provinsi');
        return new BidangResource($bidang);
    }

    public function destroy($id)
    {
        $data = Bidang::findOrFail($id);
        $data->delete();
        return response()->json(null, 204);
    }
}