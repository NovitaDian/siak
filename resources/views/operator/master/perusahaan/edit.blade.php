@extends('layouts.user_type.operator')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">EDIT PERUSAHAAN</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Edit Perusahaan Data') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('operator.perusahaan.update', $perusahaan->id) }}" method="POST" role="form text-left">
                    @csrf
                    @method('PUT') <!-- Indicate that this is an update operation -->

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

                    <!-- Kode Perusahaan -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="perusahaan_code">{{ __('Kode Perusahaan') }}</label>
                                <input class="form-control" type="text" id="perusahaan_code" name="perusahaan_code" value="{{ old('perusahaan_code', $perusahaan->perusahaan_code) }}" required maxlength="10">
                                @error('perusahaan_code')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Nama Perusahaan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="perusahaan_name">{{ __('Nama Perusahaan') }}</label>
                                <input class="form-control" type="text" id="perusahaan_name" name="perusahaan_name" value="{{ old('perusahaan_name', $perusahaan->perusahaan_name) }}" required>
                                @error('perusahaan_name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kota Perusahaan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">{{ __('Kota Perusahaan') }}</label>
                                <input class="form-control" type="text" id="city" name="city" value="{{ old('city', $perusahaan->city) }}" required>
                                @error('city')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Alamat Perusahaan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="street">{{ __('Alamat Lengkap') }}</label>
                                <input class="form-control" type="text" id="street" name="street" value="{{ old('street', $perusahaan->street) }}" required>
                                @error('street')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection