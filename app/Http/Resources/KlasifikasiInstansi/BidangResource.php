<?php

namespace App\Http\Resources\KlasifikasiInstansi;

use Illuminate\Http\Resources\Json\JsonResource;

class BidangResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'kode_bidang' => $this->kode_bidang,
            'nama_bidang' => $this->nama_bidang,
            'kode' => $this->kode,
            'kabupaten_kota' => $this->whenLoaded('kabupaten_kota', function () {
                return [
                    'id' => $this->kabupaten_kota->id,
                    'kode_kabupaten_kota' => $this->kabupaten_kota->kode_kabupaten_kota,
                    'nama_kabupaten_kota' => $this->kabupaten_kota->nama_kabupaten_kota,
                    'provinsi' => $this->kabupaten_kota->provinsi ? [
                        'id' => $this->kabupaten_kota->provinsi->id,
                        'kode_provinsi' => $this->kabupaten_kota->provinsi->kode_provinsi,
                        'nama_provinsi' => $this->kabupaten_kota->provinsi->nama_provinsi,
                    ] : null,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}