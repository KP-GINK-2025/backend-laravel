@extends('admin.layouts.main')

@section('importheadAppend')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12">
                <h3 class="content-header-title mb-0">Kelompok Aset</h3>
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
                        <h3 class="card-title">Form Kelompok Aset</h3>
                    </div>
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end mb-md-0 mb-2">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <a class="btn btn-outline-primary" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <form action="admin/kelompokaset{{ isset($data['nama_kelompok_aset']) ? '/' . request()->segment(3) : null }}" autocomplete="off" method="POST" class="ajax">
                @if (isset($data['nama_kelompok_aset']))
                    @method('PUT')
                @endif
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="akun_aset_id" class="form-label">akun_aset</label>
                                <select class="form-control form-control-sm" name="akun_aset_id" id="akun_aset_id" required>
                                    <option value="">-- Pilih akun_aset --</option>
                                    @foreach($akunasets as $akun_aset)
                                        <option value="{{ $akun_aset->id }}"
                                            {{ (isset($data['akun_aset_id']) && $data['akun_aset_id'] == $akun_aset->id) ? 'selected' : '' }}>
                                            {{ $akun_aset->kode_akun_aset }} - {{ $akun_aset->nama_akun_aset }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-2">
                            <div class="form-group">
                                <label for="kode_kelompok_aset" class="form-label">Kode kelompok_aset</label>
                                <input type="number" class="form-control form-control-sm" name="kode_kelompok_aset" placeholder="Silahkan Isi Disini" value="{{ isset($data['kode_kelompok_aset']) ? $data['kode_kelompok_aset'] : null }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="nama_kelompok_aset" class="form-label">Nama kelompok_aset</label>
                                <input type="text" class="form-control form-control-sm" name="nama_kelompok_aset" placeholder="Silahkan Isi Disini" value="{{ isset($data['nama_kelompok_aset']) ? $data['nama_kelompok_aset'] : null }}" required>
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
@endsection
