@extends('layouts.user_type.auth')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">FORMULIR EDIT BARANG</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%;">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Edit Data Barang') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('adminsystem.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
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
                        <div class="col-md-6">
                            <label for="material_code">Kode Barang</label>
                            <input class="form-control" type="text" id="material_code" name="material_code" value="{{ old('material_code', $barang->material_code) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="material_group_id">Grup Material</label>
                            <select class="form-control" id="material_group_id" name="material_group_id" required>
                                <option value="">Pilih Grup Material</option>
                                @foreach($material_groups as $group)
                                <option value="{{ $group->id }}" {{ old('material_group_id', $barang->material_group_id) == $group->id ? 'selected' : '' }}>
                                    {{ $group->material_group }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="description">Deskripsi Barang</label>
                            <input class="form-control" type="text" id="description" name="description" value="{{ old('description', $barang->description) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="material_type">Tipe Material</label>
                            <select class="form-control" id="material_type" name="material_type" required>
                                <option value="STD:Stock" {{ old('material_type', $barang->material_type) == 'STD:Stock' ? 'selected' : '' }}>STD:Stock</option>
                                <option value="STD:NonStock" {{ old('material_type', $barang->material_type) == 'STD:NonStock' ? 'selected' : '' }}>STD:NonStock</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="unit_id">Unit</label>
                            <select class="form-control" id="unit_id" name="unit_id" required>
                                <option value="">Pilih Unit</option>
                                @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id', $barang->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->unit }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="remark">Remark</label>
                            <select class="form-control" id="remark" name="remark" required>
                                <option value="K (Cost Center)" {{ old('remark', $barang->remark) == 'K (Cost Center)' ? 'selected' : '' }}>K (Cost Center)</option>
                                <option value="F (Project)" {{ old('remark', $barang->remark) == 'F (Project)' ? 'selected' : '' }}>F (Project)</option>
                                <option value="A (Asset)" {{ old('remark', $barang->remark) == 'A (Asset)' ? 'selected' : '' }}>A (Asset)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="image">Gambar Barang</label>
                            <input class="form-control" type="file" id="image" name="image" accept="image/*">
                            @if($barang->image)
                            <small class="d-block mt-2">Gambar saat ini: <img src="{{ asset('storage/images/' . $barang->image) }}" alt="barang" style="max-height: 100px;"></small>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn bg-gradient-dark btn-md">{{ __('Update Barang') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
