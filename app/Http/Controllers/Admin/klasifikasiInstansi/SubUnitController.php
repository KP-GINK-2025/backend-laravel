<?php

namespace App\Http\Controllers\Admin\klasifikasiInstansi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KlasifikasiInstansi\SubUnitResource;
use Illuminate\Support\Facades\Validator;
use App\Models\klasifikasiInstansi\SubUnit;

class SubUnitController extends Controller
{
    public function index(Request $request)
    {
        $sub_units = SubUnit::with('unit.bidang.kabupaten_kota.provinsi')->paginate(10);
        return SubUnitResource::collection($sub_units);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required|exists:units,id',
            'kode_sub_unit' => 'required|max:255|unique:sub_units,kode_sub_unit',
            'nama_sub_unit' => 'required|max:255|unique:sub_units,kode_sub_unit',
            'kode' => 'required',
        ], [
            'unit_id.required' => 'unit harus dipilih',
            'unit_id.exists' => 'unit tidak valid',
            'kode_sub_unit.required' => 'Kode sub unit harus diisi',
            'kode_sub_unit.unique' => 'Kode sub unit sudah terdaftar',
            'nama_sub_unit.required' => 'Nama sub unit harus diisi',
            'nama_sub_unit.unique' => 'Nama sub unit sudah terdaftar',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $sub_unit = SubUnit::create($validator->validated());
        $sub_unit->load('unit.bidang.kabupaten_kota.provinsi');
        return (new SubUnitResource($sub_unit))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $sub_unit = SubUnit::with('unit.bidang.kabupaten_kota.provinsi')->find($id);
        if (!$sub_unit) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return new SubUnitResource($sub_unit);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required|exists:units,id',
            'kode_sub_unit' => 'required|max:255|unique:sub_units,kode_sub_unit,' . $id,
            'nama_sub_unit' => 'required|max:255|unique:sub_units,kode_sub_unit,',
            'kode' => 'required',
        ], [
            'unit_id.required' => 'unit harus dipilih',
            'unit_id.exists' => 'unit tidak valid',
            'kode_sub_unit.required' => 'Kode sub_unit harus diisi',
            'kode_sub_unit.unique' => 'Kode sub_unit sudah terdaftar',
            'nama_sub_unit.required' => 'Nama sub_unit harus diisi',
            'nama_sub_unit.unique' => 'Nama sub unit sudah terdaftar',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $sub_unit = SubUnit::findOrFail($id);
        $sub_unit->update($validator->validated());
        $sub_unit->load('unit.bidang.kabupaten_kota.provinsi');
        return new SubUnitResource($sub_unit);
    }

    public function destroy($id)
    {
        $sub_unit = SubUnit::findOrFail($id);
        $sub_unit->delete();
        return response()->json(null, 204);
    }
}