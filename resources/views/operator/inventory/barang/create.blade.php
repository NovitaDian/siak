@extends('layouts.user_type.operator')

@section('content')

<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">FORMULIR TAMBAH BARANG</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Tambah Barang Baru') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('operator.barang.store') }}" method="POST" role="form text-left" enctype="multipart/form-data">
                    @csrf
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
                                <input class="form-control" type="text" id="material_code" name="material_code" value="{{ old('material_code') }}" required>
                                @error('material_code')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material_group_id">{{ __('Grup Material') }}</label>
                                <select class="form-control" id="material_group_id" name="material_group_id" required>
                                    <option value="">Pilih Grup Material</option>
                                    @foreach($material_groups as $material_group)
                                    <option value="{{ $material_group->id }}" {{ old('id') == $material_group->id ? 'selected' : '' }}>
                                        {{ $material_group->material_group }}
                                    </option>
                                    @endforeach
                                </select> 
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
                                <input class="form-control" type="text" id="description" name="description" value="{{ old('description') }}" required>
                                @error('description')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material_type">{{ __('Tipe Material') }}</label>
                                <select class="form-control" id="material_type" name="material_type" required>
                                    <option value="STD:Stock" {{ old('material_type') == 'STD:Stock' ? 'selected' : '' }}>STD:Stock</option>
                                    <option value="STD:NonStock" {{ old('material_type') == 'STD:NonStock' ? 'selected' : '' }}>STD:NonStock</option>
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
                                <label for="unit_id">{{ __('Unit') }}</label>
                                <select class="form-control" id="unit_id" name="unit_id" required>
                                    <option value="">Pilih Unit</option>
                                    @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->unit}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('unit')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="remark">{{ __('Remark') }}</label>
                                <select class="form-control" id="remark" name="remark" required>
                                    <option value="K (Cost Center)" {{ old('remark') == 'K (Cost Center)' ? 'selected' : '' }}>K (Cost Center)</option>
                                    <option value="F (Project)" {{ old('remark') == 'F (Project)' ? 'selected' : '' }}>F (Project)</option>
                                    <option value="A (Asset)" {{ old('remark') == 'A (Asset)' ? 'selected' : '' }}>A (Asset)</option>
                                </select>
                                @error('remark')
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
                                @error('image')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Simpan Barang') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection