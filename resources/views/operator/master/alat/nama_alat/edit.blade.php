@extends('layouts.user_type.operator')

@section('content')

<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">EDIT DATA NAMA ALAT</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('EDIT NAMA ALAT') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('operator.nama_alat.nama_alat_update', $nama_alat->id) }}" method="POST" role="form text-left">
                    @csrf
                    @method('PUT') <!-- Menandakan bahwa ini adalah request untuk update -->
                    @if($errors->any())
                    <div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">{{ $errors->first() }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nama_alat">{{ __('Nama Alat') }}</label>
                            <input class="form-control" type="text" id="nama_alat" name="nama_alat" value="{{ old('nama_alat', $nama_alat->nama_alat) }}" required>
                            @error('nama_alat')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
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