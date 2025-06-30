@extends('layouts.user_type.operator')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">EDIT DATA ALAT</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%;">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('FORM EDIT ALAT') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('operator.detail_alat.update', $alat->id) }}" method="POST">
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
                        {{-- Nama Alat --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_alat_id">{{ __('Nama Alat') }}</label>
                                <select class="form-control" id="nama_alat_id" name="nama_alat_id" required>
                                    <option value="">-- Pilih Alat --</option>
                                    @foreach($nama_alats as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('nama_alat_id', $alat->nama_alat_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_alat }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('nama_alat_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Nomor Alat --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nomor">{{ __('Nomor Alat') }}</label>
                                <input class="form-control" type="text" id="nomor" name="nomor"
                                    value="{{ old('nomor', $alat->nomor) }}" required>
                                @error('nomor')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Durasi Inspeksi --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="durasi_inspeksi">{{ __('Durasi Inspeksi (hari)') }}</label>
                                <input class="form-control" type="number" id="durasi_inspeksi" name="durasi_inspeksi"
                                    value="{{ old('durasi_inspeksi', $alat->durasi_inspeksi) }}" min="0" required>
                                @error('durasi_inspeksi')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Update Data') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection