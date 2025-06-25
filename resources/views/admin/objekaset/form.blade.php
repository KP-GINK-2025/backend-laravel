@extends('admin.layouts.main')

@section('importheadAppend')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12">
                <h3 class="content-header-title mb-0">Objek Aset</h3>
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
                        <h3 class="card-title">Form Objek Aset</h3>
                    </div>
                    <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end mb-md-0 mb-2">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <a class="btn btn-outline-primary" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <form action="admin/objekaset{{ isset($data->nama_objek_aset) ? '/' . request()->segment(3) : null }}" autocomplete="off" method="POST" class="ajax">
                @if (isset($data->nama_objek_aset))
                    @method('PUT')
                @endif
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="akun_aset_id" class="form-label">Akun Aset</label>
                                <select class="form-control form-control-sm" name="akun_aset_id" id="akun_aset_id" required>
                                    <option value="">-- Pilih Akun Aset --</option>
                                    @foreach($akunasets as $akun_aset)
                                        <option value="{{ $akun_aset->id }}"
                                            {{ (isset($data->akun_aset_id) && $data->akun_aset_id == $akun_aset->id) ? 'selected' : '' }}>
                                            {{ $akun_aset->kode_akun_aset }} - {{ $akun_aset->nama_akun_aset }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="kelompok_aset_id" class="form-label">Kelompok Aset</label>
                                <select class="form-control form-control-sm" name="kelompok_aset_id" id="kelompok_aset_id" required>
                                    <option value="">-- Pilih Kelompok Aset --</option>
                                    @foreach($kelompokasets as $kelompok_aset)
                                        <option value="{{ $kelompok_aset->id }}"
                                            data-akun_aset="{{ $kelompok_aset->akun_aset_id }}"
                                            {{ (isset($data->kelompok_aset_id) && $data->kelompok_aset_id == $kelompok_aset->id) ? 'selected' : '' }}>
                                            {{ $kelompok_aset->kode_kelompok_aset }} - {{ $kelompok_aset->nama_kelompok_aset }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="jenis_aset_id" class="form-label">jenis Aset</label>
                                <select class="form-control form-control-sm" name="jenis_aset_id" id="jenis_aset_id" required>
                                    <option value="">-- Pilih jenis Aset --</option>
                                    @foreach($jenisasets as $jenis_aset)
                                        <option value="{{ $jenis_aset->id }}"
                                            data-akun_aset="{{ $jenis_aset->akun_aset_id }}"
                                            data-kelompok_aset="{{ $jenis_aset->kelompok_aset_id }}"
                                            {{ (isset($data->jenis_aset_id) && $data->jenis_aset_id == $jenis_aset->id) ? 'selected' : '' }}>
                                            {{ $jenis_aset->kode_jenis_aset }} - {{ $jenis_aset->nama_jenis_aset }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-2">
                            <div class="form-group">
                                <label for="kode_objek_aset" class="form-label">Kode objek Aset</label>
                                <input type="number" class="form-control form-control-sm" name="kode_objek_aset" placeholder="Silahkan Isi Disini" value="{{ isset($data->kode_objek_aset) ? $data->kode_objek_aset : null }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <div class="form-group">
                                <label for="nama_objek_aset" class="form-label">Nama objek Aset</label>
                                <input type="text" class="form-control form-control-sm" name="nama_objek_aset" placeholder="Silahkan Isi Disini" value="{{ isset($data->nama_objek_aset) ? $data->nama_objek_aset : null }}" required>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-2">
                            <div class="form-group">
                                <label for="kode" class="form-label">Kode</label>
                                <input type="text" class="form-control form-control-sm" name="kode" placeholder="Silahkan Isi Disini" value="{{ isset($data->kode) ? $data->kode : null }}">
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