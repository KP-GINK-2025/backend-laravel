<?php

namespace App\Http\Resources\KlasifikasiInstansi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
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
            'bidang_id' => $this->bidang_id,
            'kode_unit' => $this->kode_unit,
            'nama_unit' => $this->nama_unit,
            'kode' => $this->kode,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'bidang' => $this->whenLoaded('bidang', function () {
                return [
                    'id' => $this->bidang->id ?? null,
                    'kode_bidang' => $this->bidang->kode_bidang ?? null,
                    'nama_bidang' => $this->bidang->nama_bidang ?? null,
                    'kabupaten_kota' => $this->bidang->kabupaten_kota ? [
                        'id' => $this->bidang->kabupaten_kota->id ?? null,
                        'kode_kabupaten_kota' => $this->bidang->kabupaten_kota->kode_kabupaten_kota ?? null,
                        'nama_kabupaten_kota' => $this->bidang->kabupaten_kota->nama_kabupaten_kota ?? null,
                        'provinsi' => $this->bidang->kabupaten_kota->provinsi ? [
                            'id' => $this->bidang->kabupaten_kota->provinsi->id ?? null,
                            'kode_provinsi' => $this->bidang->kabupaten_kota->provinsi->kode_provinsi ?? null,
                            'nama_provinsi' => $this->bidang->kabupaten_kota->provinsi->nama_provinsi ?? null,
                        ] : null,
                    ] : null,
                ];
            }),
        ];
    }
}
