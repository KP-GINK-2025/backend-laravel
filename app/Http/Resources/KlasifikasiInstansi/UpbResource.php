<?php

namespace App\Http\Resources\KlasifikasiInstansi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpbResource extends JsonResource
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
            'sub_unit_id' => $this->sub_unit_id,
            'kode_upb' => $this->kode_upb,
            'nama_upb' => $this->nama_upb,
            'kode' => $this->kode,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sub_unit' => $this->whenLoaded('sub_unit', function () {
                return [
                    'id' => $this->sub_unit->id ?? null,
                    'kode_sub_unit' => $this->sub_unit->kode_sub_unit ?? null,
                    'nama_sub_unit' => $this->sub_unit->nama_sub_unit ?? null,
                    'unit' => $this->sub_unit->unit ? [
                        'id' => $this->sub_unit->unit->id ?? null,
                        'kode_unit' => $this->sub_unit->unit->kode_unit ?? null,
                        'nama_unit' => $this->sub_unit->unit->nama_unit ?? null,
                        'bidang' => $this->sub_unit->unit->bidang ? [
                            'id' => $this->sub_unit->unit->bidang->id ?? null,
                            'kode_bidang' => $this->sub_unit->unit->bidang->kode_bidang ?? null,
                            'nama_bidang' => $this->sub_unit->unit->bidang->nama_bidang ?? null,
                            'kabupaten_kota' => $this->sub_unit->unit->bidang->kabupaten_kota ? [
                                'id' => $this->sub_unit->unit->bidang->kabupaten_kota->id ?? null,
                                'kode_kabupaten_kota' => $this->sub_unit->unit->bidang->kabupaten_kota->kode_kabupaten_kota ?? null,
                                'nama_kabupaten_kota' => $this->sub_unit->unit->bidang->kabupaten_kota->nama_kabupaten_kota ?? null,
                                'provinsi' => $this->sub_unit->unit->bidang->kabupaten_kota->provinsi ? [
                                    'id' => $this->sub_unit->unit->bidang->kabupaten_kota->provinsi->id ?? null,
                                    'kode_provinsi' => $this->sub_unit->unit->bidang->kabupaten_kota->provinsi->kode_provinsi ?? null,
                                    'nama_provinsi' => $this->sub_unit->unit->bidang->kabupaten_kota->provinsi->nama_provinsi ?? null,
                                ] : null,
                            ] : null,
                        ] : null,
                    ] : null,
                ];
            }),
        ];
    }
}
