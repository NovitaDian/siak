@extends('layouts.user_type.auth')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">EDIT LAPORAN PENGECEKAN ALAT</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('UBAH DATA PEMERIKSAAN ALAT') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('adminsystem.tool.sent_update', $tool_fixs->id) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                    <div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">{{ $errors->first() }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    <div class="row">
                        <!-- Alat ID -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alat_id">{{ __('Alat') }}</label>
                                <select class="form-control" name="alat_id" id="alat_id" required>
                                    @foreach($alats as $alat)
                                    <option value="{{ $alat->id }}" {{ $tool_fixs->alat_id == $alat->id ? 'selected' : '' }}>{{ $alat->nama_alat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- HSE Inspector ID -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hse_inspector_id">{{ __('HSE Inspector') }}</label>
                                <select class="form-control" name="hse_inspector_id" id="hse_inspector_id" required>
                                    @foreach($inspectors as $inspector)
                                    <option value="{{ $inspector->id }}" {{ $tool_fixs->hse_inspector_id == $inspector->id ? 'selected' : '' }}>{{ $inspector->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Tanggal Pemeriksaan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_pemeriksaan">{{ __('Tanggal Pemeriksaan') }}</label>
                                <input class="form-control" type="date" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" value="{{ old('tanggal_pemeriksaan', $tool_fixs->tanggal_pemeriksaan) }}" required>
                            </div>
                        </div>

                        <!-- status_pemeriksaan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status_pemeriksaan">{{ __('Status Pemeriksaan') }}</label>
                                <select name="status_pemeriksaan" class="form-control" required>
                                    <option value="Layak operasi" {{ $tool_fixs->status_pemeriksaan == 'Layak operasi' ? 'selected' : '' }}>Layak operasi</option>
                                    <option value="Layak operasi dengan catatan" {{ $tool_fixs->status_pemeriksaan == 'Layak operasi dengan catatan' ? 'selected' : '' }}>Layak operasi dengan catatan</option>
                                    <option value="Tidak layak operasi" {{ $tool_fixs->status_pemeriksaan == 'Tidak layak operasi' ? 'selected' : '' }}>Tidak layak operasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="foto">Foto (biarkan kosong jika tidak ingin ubah)</label>
                            <input
                                type="file"
                                name="foto"
                                id="foto"
                                class="form-control"
                                accept="image/*"
                                @if (!$tool_fixs->foto)
                            @endif
                            >
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Update Laporan') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection