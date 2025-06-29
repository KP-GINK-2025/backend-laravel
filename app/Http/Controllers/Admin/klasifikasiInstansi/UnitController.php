<?php

namespace App\Http\Controllers\Admin\klasifikasiInstansi;

use App\Http\Controllers\Controller;
use App\Models\klasifikasiInstansi\Unit;
use App\Http\Resources\KlasifikasiInstansi\UnitResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $units = Unit::with('bidang.kabupaten_kota.provinsi')->paginate(10);
        return UnitResource::collection($units);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bidang_id' => 'required|exists:bidangs,id',
            'kode_unit' => 'required|max:255|unique:units,kode_unit',
            'nama_unit' => 'required|max:255|unique:units,nama_unit',
            'kode' => 'required',
        ], [
            'bidang_id.required' => 'Bidang harus dipilih',
            'bidang_id.exists' => 'Bidang tidak valid',
            'kode_unit.required' => 'Kode unit harus diisi',
            'kode_unit.unique' => 'Kode unit sudah terdaftar',
            'nama_unit.required' => 'Nama unit harus diisi',
            'nama_unit.unique' => 'Nama unit sudah terdaftar',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $unit = Unit::create($validator->validated());
        $unit->load('bidang.kabupaten_kota.provinsi');
        return (new UnitResource($unit))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $unit = Unit::with('bidang.kabupaten_kota.provinsi')->find($id);
        if (!$unit) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return new UnitResource($unit);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bidang_id' => 'required|exists:bidangs,id',
            'kode_unit' => 'required|max:255|unique:units,kode_unit,' . $id,
            'nama_unit' => 'required|max:255|unique:units,nama_unit,',
            'kode' => 'required',
        ], [
            'bidang_id.required' => 'Bidang harus dipilih',
            'bidang_id.exists' => 'Bidang tidak valid',
            'kode_unit.required' => 'Kode unit harus diisi',
            'kode_unit.unique' => 'Kode unit sudah terdaftar',
            'nama_unit.required' => 'Nama unit harus diisi',
            'nama_unit.unique' => 'Nama unit sudah terdaftar',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $unit = Unit::findOrFail($id);
        $unit->update($validator->validated());
        $unit->load('bidang.kabupaten_kota.provinsi');
        return new UnitResource($unit);
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();
        return response()->json(null, 204);
    }
}