@extends('admin.layouts.main')

@section('importheadAppend')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12">
                <h3 class="content-header-title mb-0">UPB</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            @foreach ($breadcrumbs as $item)
                                @if (!$item['disabled'])
                                    <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
                                @else
                                    <li class="breadcrumb-item active">{{ $item['title'] }}</li>
                                @endif
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-start align-items-center mb-3 mb-md-0">
                        <h3 class="card-title">Form Sub Unit</h3>
                    </div>
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end mb-md-0 mb-2">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <a class="btn btn-outline-primary" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <form action="admin/upb{{ isset($data['nama_upb']) ? '/' . request()->segment(3) : null }}" autocomplete="off" method="POST" class="ajax">
                @if (isset($data['nama_upb']))
                    @method('PUT')
                @endif
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="provinsi_id" class="form-label">Provinsi</label>
                                <select class="form-control form-control-sm" name="provinsi_id" id="provinsi_id" required>
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinsis as $provinsi)
                                        <option value="{{ $provinsi->id }}"
                                            {{ (isset($data->provinsi_id) && $data->provinsi_id == $provinsi->id) ? 'selected' : '' }}>
                                            {{ $provinsi->kode_provinsi }} - {{ $provinsi->nama_provinsi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="kabupaten_kota_id" class="form-label">Kabupaten/Kota</label>
                                <select class="form-control form-control-sm" name="kabupaten_kota_id" id="kabupaten_kota_id" required>
                                    <option value="">-- Pilih Kabupaten/Kota --</option>
                                    @foreach($kabupaten_kotas as $kabupaten_kota)
                                        <option value="{{ $kabupaten_kota->id }}"
                                            data-provinsi="{{ $kabupaten_kota->provinsi_id }}"
                                            {{ (isset($data->kabupaten_kota_id) && $data->kabupaten_kota_id == $kabupaten_kota->id) ? 'selected' : '' }}>
                                            {{ $kabupaten_kota->kode_kabupaten_kota }} - {{ $kabupaten_kota->nama_kabupaten_kota }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="bidang_id" class="form-label">Bidang</label>
                                <select class="form-control form-control-sm" name="bidang_id" id="bidang_id" required>
                                    <option value="">-- Pilih Bidang --</option>
                                    @foreach($bidangs as $bidang)
                                        <option value="{{ $bidang->id }}"
                                            data-provinsi="{{ $bidang->kabupaten_kota->provinsi_id ?? '' }}"
                                            data-kabupaten_kota="{{ $bidang->kabupaten_kota_id ?? '' }}"
                                            {{ (isset($data['bidang_id']) && $data['bidang_id'] == $bidang->id) ? 'selected' : '' }}>
                                            {{ $bidang->kode_bidang }} - {{ $bidang->nama_bidang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="unit_id" class="form-label">Unit</label>
                                <select class="form-control form-control-sm" name="unit_id" id="unit_id" required>
                                    <option value="">-- Pilih Unit --</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            data-provinsi="{{ $unit->bidang->kabupaten_kota->provinsi_id ?? '' }}"
                                            data-kabupaten_kota="{{ $unit->bidang->kabupaten_kota_id ?? '' }}"
                                            data-bidang="{{ $unit->bidang_id }}"
                                            {{ (isset($data->unit_id) && $data->unit_id == $unit->id) ? 'selected' : '' }}>
                                            {{ $unit->kode_unit }} - {{ $unit->nama_unit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="sub_unit_id" class="form-label">Sub Unit</label>
                                <select class="form-control form-control-sm" name="sub_unit_id" id="sub_unit_id" required>
                                    <option value="">-- Pilih Sub Unit --</option>
                                    @foreach($sub_units as $sub_unit)
                                        <option value="{{ $sub_unit->id }}"
                                            data-provinsi="{{ $unit->bidang->kabupaten_kota->provinsi_id ?? '' }}"
                                            data-kabupaten_kota="{{ $unit->bidang->kabupaten_kota_id ?? '' }}"
                                            data-bidang="{{ $unit->bidang_id }}"
                                            data-unit="{{ $sub_unit->unit_id }}"
                                            data-bidang="{{ $sub_unit->unit->bidang_id}}"
                                            {{ (isset($data->sub_unit_id) && $data->sub_unit_id == $sub_unit->id) ? 'selected' : '' }}>
                                            {{ $sub_unit->kode_sub_unit }} - {{ $sub_unit->nama_sub_unit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-2">
                            <div class="form-group">
                                <label for="kode_upb" class="form-label">Kode UPB</label>
                                <input type="number" class="form-control form-control-sm" name="kode_upb" placeholder="Silahkan Isi Disini" value="{{ isset($data['kode_upb']) ? $data['kode_upb'] : null }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="nama_upb" class="form-label">Nama UPB</label>
                                <input type="text" class="form-control form-control-sm" name="nama_upb" placeholder="Silahkan Isi Disini" value="{{ isset($data['nama_upb']) ? $data['nama_upb'] : null }}" required>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-2">
                            <div class="form-group">
                                <label for="kode" class="form-label">Kode</label>
                                <input type="text" class="form-control form-control-sm" name="kode" placeholder="Silahkan Isi Disini" value="{{ isset($data['kode']) ? $data['kode'] : null }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer" style="border: none; background: transparent;">
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-sm btn-outline-primary text-white">Save <i class="fa fa-fw fa-save"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('importfootAppend')
    <script>
        $(document).ready(function() {
            $('#bidang_id').on('change', function() {
                var selected = $(this).find('option:selected');
                var provinsiId = selected.data('provinsi');
                var kabupaten_kotaId = selected.data('kabupaten_kota');
                if(provinsiId){
                    $('#provinsi_id').val(provinsiId);
                }
                if(kabupaten_kotaId){
                    $('#kabupaten_kota_id').val(kabupaten_kotaId);
                }
            });
            // Saat kabupaten_kota dipilih, provinsi otomatis terisi sesuai relasi kabupaten_kota
            $('#kabupaten_kota_id').on('change', function() {
                var provinsiId = $(this).find('option:selected').data('provinsi');
                if(provinsiId){
                    $('#provinsi_id').val(provinsiId);
                }
            });

            // Saat provinsi diganti manual, reset kabupaten_kota
            $('#provinsi_id').on('change', function() {
                $('#kabupaten_kota_id').val('');
            });
            // Jika unit dipilih, bidang otomatis terisi
            $('#unit_id').on('change', function() {
                var bidangId = $(this).find('option:selected').data('bidang');
                if(bidangId){
                    $('#bidang_id').val(bidangId).trigger('change');
                }
            });

            // Jika bidang diganti manual, unit di-reset
            $('#bidang_id').on('change', function() {
                var selectedUnit = $('#unit_id').find('option:selected');
                var bidangId = $(this).val();

                if(selectedUnit.length && selectedUnit.data('bidang') != bidangId){
                    $('#unit_id').val('').trigger('change');
                }
            });

            // Saat halaman load, jika sub unit sudah terisi, isi bidang & unit sesuai relasi sub unit
            if($('#sub_unit_id').val()){
                $('#sub_unit_id').trigger('change');
            } else if($('#unit_id').val()){
                $('#unit_id').trigger('change');
            }
        });
    </script>
@endsection
