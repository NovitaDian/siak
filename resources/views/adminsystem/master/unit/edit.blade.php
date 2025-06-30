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
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div>
    <div class="container-fluid ">
        <h2 class="text-black font-weight-bolder text-center">EDIT UNIT</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('DATA UMUM') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('adminsystem.unit.update', $unit->id) }}" method="POST" role="form text-left">
                    @csrf
                    @method('PUT') <!-- Menambahkan method PUT untuk update -->
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit">{{ __('Unit') }}</label>
                                <input class="form-control" type="text" id="unit" name="unit" value="{{ old('unit', $unit->unit) }}" required>
                                @error('unit')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description">{{ __('Description') }}</label>
                                <input class="form-control" type="text" id="description" name="description" value="{{ old('description', $unit->description) }}" required>
                                @error('description')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection