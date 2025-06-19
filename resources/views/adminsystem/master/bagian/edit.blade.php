@extends('layouts.user_type.auth')

@section('content')
@if($errors->any())
<div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
  <span class="alert-text text-white">{{ $errors->first() }}</span>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
    <i class="fa fa-close" aria-hidden="true"></i>
  </button>
</div>
@endif
@if(session('success'))
<div class="m-3 alert alert-success alert-dismissible fade show" role="alert">
  <span class="alert-text text-white">{{ session('success') }}</span>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
    <i class="fa fa-close" aria-hidden="true"></i>
  </button>
</div>
@endif
<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">Edit Bagian</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Edit Data Bagian') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('adminsystem.bagian.update', $bagian->id) }}" method="POST" role="form text-left">
                    @csrf
                    @method('PUT') <!-- For PUT request when updating the record -->

                    @if($errors->any())
                    <div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">{{ $errors->first() }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    @if(session('success'))
                    <div class="m-3 alert alert-success alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif

                    <!-- Perusahaan Code Select -->
                    <div class="form-group">
                        <label for="perusahaan_code">{{ __('Perusahaan Code') }}</label>
                        <select class="form-control" id="perusahaan_code" name="perusahaan_code" required>
                            <option value="">Select Perusahaan</option>
                            @foreach($perusahaans as $perusahaan)
                            <option value="{{ $perusahaan->perusahaan_code }}"
                                {{ $perusahaan->perusahaan_code == old('perusahaan_code', $bagian->perusahaan_code) ? 'selected' : '' }}>
                                {{ $perusahaan->perusahaan_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('perusahaan_code')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hidden Perusahaan Name Input (Automatically filled by server) -->
                    <div class="form-group" style="display: none;">
                        <label for="perusahaan_name">{{ __('Perusahaan Name') }}</label>
                        <input class="form-control" type="text" id="perusahaan_name" name="perusahaan_name" value="{{ old('perusahaan_name', $bagian->perusahaan_name) }}" readonly>
                        @error('perusahaan_name')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Bagian Input -->
                    <div class="form-group">
                        <label for="nama_bagian">{{ __('Nama Bagian') }}</label>
                        <input class="form-control" type="text" id="nama_bagian" name="nama_bagian" value="{{ old('nama_bagian', $bagian->nama_bagian) }}" required>
                        @error('nama_bagian')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Update') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection