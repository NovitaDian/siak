@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">FORMULIR EDIT BARANG</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Edit Barang') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('adminsystem.barang.update', $barangs->id) }}" method="POST" role="form text-left" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- This ensures the form submits a PUT request for updates -->
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
                                <label for="material_code">{{ __('Kode Barang') }}</label>
                                <input class="form-control" type="text" id="material_code" name="material_code" value="{{ old('material_code', $barangs->material_code) }}" required>
                                @error('material_code')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material_group">{{ __('Grup Material') }}</label>
                                <input class="form-control" type="text" id="material_group" name="material_group" value="{{ old('material_group', $barangs->material_group) }}" required>
                                @error('material_group')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description">{{ __('Deskripsi Barang') }}</label>
                                <input class="form-control" type="text" id="description" name="description" value="{{ old('description', $barangs->description) }}" required>
                                @error('description')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material_type">{{ __('Tipe Material') }}</label>
                                <select class="form-control" id="material_type" name="material_type" required>
                                    <option value="STD:Stock" {{ old('material_type', $barangs->material_type) == 'STD:Stock' ? 'selected' : '' }}>STD:Stock</option>
                                    <option value="STD:NonStock" {{ old('material_type', $barangs->material_type) == 'STD:NonStock' ? 'selected' : '' }}>STD:NonStock</option>
                                </select>
                                @error('material_type')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit">{{ __('Unit') }}</label>
                                <input class="form-control" type="text" id="unit" name="unit" value="{{ old('unit', $barangs->unit) }}" required>
                                @error('unit')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="image">{{ __('Gambar Barang') }}</label>
                                <input class="form-control" type="file" id="image" name="image" accept="image/*">
                                @if($barangs->image)
                                <div class="mt-2">
                                    <p>Current Image:</p>
                                    <img src="{{ asset('storage/images/' . $barangs->image) }}" alt="Current Image" class="img-fluid" style="max-height: 200px;">
                                </div>
                                @endif
                                @error('image')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Simpan Perubahan') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection