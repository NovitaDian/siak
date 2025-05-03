@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">TAMBAH INSPEKTOR</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Tambah Inspektor HSE') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('adminsystem.hse_inspector.store') }}" method="POST" role="form text-left">
                    @csrf

                    <div class="form-group">
                        <label for="name">{{ __('Nama Inspektor') }}</label>
                        <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jabatan">{{ __('Jabatan') }}</label>
                        <input class="form-control" type="text" id="jabatan" name="jabatan" value="{{ old('jabatan') }}" required>
                        @error('jabatan')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection