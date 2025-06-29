<?php

namespace App\Http\Resources\KlasifikasiInstansi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubUnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'unit_id' => $this->unit_id,
            'kode_sub_unit' => $this->kode_sub_unit,
            'nama_sub_unit' => $this->nama_sub_unit,
            'kode' => $this->kode,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'unit' => $this->whenLoaded('unit', function () {
                return [
                    'id' => $this->unit->id ?? null,
                    'kode_unit' => $this->unit->kode_unit ?? null,
                    'nama_unit' => $this->unit->nama_unit ?? null,
                    'bidang' => $this->unit->bidang ? [
                        'id' => $this->unit->bidang->id ?? null,
                        'kode_bidang' => $this->unit->bidang->kode_bidang ?? null,
                        'nama_bidang' => $this->unit->bidang->nama_bidang ?? null,
                        'kabupaten_kota' => $this->unit->bidang->kabupaten_kota ? [
                            'id' => $this->unit->bidang->kabupaten_kota->id ?? null,
                            'kode_kabupaten_kota' => $this->unit->bidang->kabupaten_kota->kode_kabupaten_kota ?? null,
                            'nama_kabupaten_kota' => $this->unit->bidang->kabupaten_kota->nama_kabupaten_kota ?? null,
                            'provinsi' => $this->unit->bidang->kabupaten_kota->provinsi ? [
                                'id' => $this->unit->bidang->kabupaten_kota->provinsi->id ?? null,
                                'kode_provinsi' => $this->unit->bidang->kabupaten_kota->provinsi->kode_provinsi ?? null,
                                'nama_provinsi' => $this->unit->bidang->kabupaten_kota->provinsi->nama_provinsi ?? null,
                            ] : null,
                        ] : null,
                    ] : null,
                ];
            }),
        ];
    }
}
