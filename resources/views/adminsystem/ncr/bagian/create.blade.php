@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">TAMBAH BAGIAN</h2>
    </div>
    <div class="container-fluid py-4">
        <div class="card mx-auto w-100" style="max-width: 95%;">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Tambah Bagian Baru') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('adminsystem.bagian.store') }}" method="POST" role="form text-left">
                    @csrf

                    <!-- Perusahaan Code Select -->
                    <div class="form-group">
                        <label for="perusahaan_code">{{ __('Perusahaan Code') }}</label>
                        <select class="form-control" id="perusahaan_code" name="perusahaan_code" required>
                            <option value="">Select Perusahaan</option>
                            @foreach($perusahaans as $perusahaan)
                            <option value="{{ $perusahaan->perusahaan_code }}" {{ old('perusahaan_code') == $perusahaan->perusahaan_code ? 'selected' : '' }}>{{ $perusahaan->perusahaan_name }}</option>
                            @endforeach
                        </select>
                        @error('perusahaan_code')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group" style="display: none;">
                        <label for="perusahaan_name">{{ __('Perusahaan Name') }}</label>
                        <input class="form-control" type="text" id="perusahaan_name" name="perusahaan_name" value="{{ old('perusahaan_name') }}" readonly>
                        @error('perusahaan_name')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Nama Bagian Input -->
                    <div class="form-group">
                        <label for="nama_bagian">{{ __('Nama Bagian') }}</label>
                        <input class="form-control" type="text" id="nama_bagian" name="nama_bagian" value="{{ old('nama_bagian') }}" required>
                        @error('nama_bagian')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Save') }}</button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection