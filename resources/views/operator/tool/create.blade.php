@extends('layouts.user_type.operator')

@section('content')

<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">TAMBAH LAPORAN PENGECEKAN ALAT</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('DATA LAPORAN PENGECEKAN ALAT') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('operator.tool.store') }}" method="POST" enctype="multipart/form-data" role="form text-left">
                    @csrf
                    @if($errors->any())
                    <div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">{{ $errors->first() }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    <div class="row">
                        <!-- Alat ID -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alat_id">{{ __('Alat') }}</label>
                                <select class="form-control" name="alat_id" id="alat_id" required>
                                    <option value="">Pilih Alat</option>
                                    @foreach($alats as $alat)
                                    <option value="{{ $alat->id }}" {{ old('alat_id') == $alat->id ? 'selected' : '' }}>{{ $alat->nama_alat }}-{{ $alat->nomor }}</option>
                                    @endforeach
                                </select>
                                @error('alat_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- HSE Inspector ID -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hse_inspector_id">{{ __('HSE Inspector') }}</label>
                                <select class="form-control" name="hse_inspector_id" id="hse_inspector_id" required>
                                    <option value="">Pilih HSE Inspector</option>
                                    @foreach($inspectors as $inspector)
                                    <option value="{{ $inspector->id }}" {{ old('hse_inspector_id') == $inspector->id ? 'selected' : '' }}>{{ $inspector->name }}</option>
                                    @endforeach
                                </select>
                                @error('hse_inspector_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanggal Pemeriksaan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_pemeriksaan">{{ __('Tanggal Pemeriksaan') }}</label>
                                <input class="form-control" type="date" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" value="{{ old('tanggal_pemeriksaan') }}" required>
                                @error('tanggal_pemeriksaan')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status_pemeriksaan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status_pemeriksaan">{{ __('Status_pemeriksaan') }}</label>
                                <select class="form-control" name="status_pemeriksaan" id="status_pemeriksaan" required>
                                    <option value="">Pilih Status pemeriksaan</option>
                                    <option value="Layak operasi" {{ old('status_pemeriksaan') == 'Layak operasi' ? 'selected' : '' }}>Layak Operasi</option>
                                    <option value="Layak operasi dengan catatan" {{ old('status_pemeriksaan') == 'Layak operasi dengan catatan' ? 'selected' : '' }}>Layak Operasi Dengan Catatan</option>
                                    <option value="Tidak layak operasi" {{ old('status_pemeriksaan') == 'Tidak layak operasi' ? 'selected' : '' }}>Tidak Layak Operasi</option>
                                </select>
                                @error('status_pemeriksaan')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto">{{ __('Foto Report') }}</label>
                                <input
                                    class="form-control @error('foto') is-invalid @enderror"
                                    type="file"
                                    id="foto"
                                    name="foto"
                                    accept="image/*"
                                    capture="environment"
                                    required>
                                @error('foto')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Save Report') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection