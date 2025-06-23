@extends('admin.layouts.main')

@section('importheadAppend')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12">
                <h3 class="content-header-title mb-0">Sub Unit</h3>
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
            <form action="admin/subunit{{ isset($data['nama_sub_unit']) ? '/' . request()->segment(3) : null }}" autocomplete="off" method="POST" class="ajax">
                @if (isset($data['nama_sub_unit']))
                    @method('PUT')
                @endif
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="bidang_id" class="form-label">Bidang</label>
                                <select class="form-control form-control-sm" name="bidang_id" id="bidang_id" required>
                                    <option value="">-- Pilih Bidang --</option>
                                    @foreach($bidangs as $bidang)
                                        <option value="{{ $bidang->id }}"
                                            {{ (isset($data->bidang_id) && $data->bidang_id == $bidang->id) ? 'selected' : '' }}>
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
                                            data-bidang="{{ $unit->bidang_id }}"
                                            {{ (isset($data->unit_id) && $data->unit_id == $unit->id) ? 'selected' : '' }}>
                                            {{ $unit->kode_unit }} - {{ $unit->nama_unit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-2">
                            <div class="form-group">
                                <label for="kode_sub_unit" class="form-label">Kode Sub Unit</label>
                                <input type="number" class="form-control form-control-sm" name="kode_sub_unit" placeholder="Silahkan Isi Disini" value="{{ isset($data['kode_sub_unit']) ? $data['kode_sub_unit'] : null }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="nama_sub_unit" class="form-label">Nama Sub Unit</label>
                                <input type="text" class="form-control form-control-sm" name="nama_sub_unit" placeholder="Silahkan Isi Disini" value="{{ isset($data['nama_sub_unit']) ? $data['nama_sub_unit'] : null }}" required>
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
        
        $('#unit_id').trigger('change');
    });
</script>
@endsection
