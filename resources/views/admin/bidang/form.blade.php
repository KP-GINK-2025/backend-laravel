@extends('admin.layouts.main')

@section('importheadAppend')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12">
                <h3 class="content-header-title mb-0">Bidang</h3>
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
                        <h3 class="card-title">Form Bidang</h3>
                    </div>
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end mb-md-0 mb-2">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <a class="btn btn-outline-primary" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <form action="admin/bidang{{ isset($data['nama_bidang']) ? '/' . request()->segment(3) : null }}" autocomplete="off" method="POST" class="ajax">
                @if (isset($data['nama_bidang']))
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
                        <div class="col-sm-3 mb-2">
                            <div class="form-group">
                                <label for="kode_bidang" class="form-label">Kode Bidang</label>
                                <input type="number" class="form-control form-control-sm" name="kode_bidang" placeholder="Silahkan Isi Disini" value="{{ isset($data['kode_bidang']) ? $data['kode_bidang'] : null }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="nama_bidang" class="form-label">Nama Bidang</label>
                                <input type="text" class="form-control form-control-sm" name="nama_bidang" placeholder="Silahkan Isi Disini" value="{{ isset($data['nama_bidang']) ? $data['nama_bidang'] : null }}" required>
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
        $('#kabupaten_kota_id').on('change', function() {
            var provinsiId = $(this).find('option:selected').data('provinsi');
            if(provinsiId){
                $('#provinsi_id').val(provinsiId).trigger('change');
            }
        });

        $('#provinsi_id').on('change', function() {
            var selectedKabupatenKota = $('#kabupaten_kota_id').find('option:selected');
            var provinsiId = $(this).val();

            if(selectedKabupatenKota.length && selectedKabupatenKota.data('provinsi') != provinsiId){
                $('#kabupaten_kota_id').val('').trigger('change');
            }
        });
        
        $('#kabupaten_kota_id').trigger('change');
    });
</script>
@endsection
