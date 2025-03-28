@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">TAMBAH LAPORAN K3</h2>
    </div>
    <div class="container-fluid py-4">
        <div class="card mx-auto w-100" style="max-width: 95%;">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('DATA LAPORAN PENGECEKAN ALAT') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('adminsystem.tool.store') }}" method="POST" role="form text-left">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alat_terpakai">{{ __('Alat yang Digunakan') }}</label>
                                <input class="form-control" type="text" id="alat_terpakai" name="alat_terpakai" value="{{ old('alat_terpakai') }}" required>
                                @error('alat_terpakai')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kondisi_fisik">{{ __('Kondisi Fisik') }}</label>
                                <input class="form-control" type="text" id="kondisi_fisik" name="kondisi_fisik" value="{{ old('kondisi_fisik') }}" required>
                                @error('kondisi_fisik')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fungsi_kerja">{{ __('Fungsi Alat') }}</label>
                                <input class="form-control" type="text" id="fungsi_kerja" name="fungsi_kerja" value="{{ old('fungsi_kerja') }}" required>
                                @error('fungsi_kerja')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sertifikasi">{{ __('Sertifikasi Alat') }}</label>
                                <select class="form-control" id="sertifikasi" name="sertifikasi" required>
                                    <option value="Ada" {{ old('sertifikasi') == 'Ada' ? 'selected' : '' }}>Ada</option>
                                    <option value="Tidak Ada" {{ old('sertifikasi') == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                </select>
                                @error('sertifikasi')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kebersihan">{{ __('Kebersihan Alat') }}</label>
                                <select class="form-control" id="kebersihan" name="kebersihan" required>
                                    <option value="Bersih" {{ old('kebersihan') == 'Bersih' ? 'selected' : '' }}>Bersih</option>
                                    <option value="Kotor" {{ old('kebersihan') == 'Kotor' ? 'selected' : '' }}>Kotor</option>
                                </select>
                                @error('kebersihan')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_pemeriksaan">{{ __('Tanggal Pemeriksaan') }}</label>
                                <input class="form-control" type="date" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" value="{{ old('tanggal_pemeriksaan') }}" required>
                                @error('tanggal_pemeriksaan')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="label_petunjuk">{{ __('Label Petunjuk') }}</label>
                                <input class="form-control" type="text" id="label_petunjuk" name="label_petunjuk" value="{{ old('label_petunjuk') }}" required>
                                @error('label_petunjuk')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pemeliharaan">{{ __('Pemeliharaan') }}</label>
                                <input class="form-control" type="text" id="pemeliharaan" name="pemeliharaan" value="{{ old('pemeliharaan') }}" required>
                                @error('pemeliharaan')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="keamanan_pengguna">{{ __('Keamanan Pengguna') }}</label>
                                <input class="form-control" type="text" id="keamanan_pengguna" name="keamanan_pengguna" value="{{ old('keamanan_pengguna') }}" required>
                                @error('keamanan_pengguna')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="catatan">{{ __('Catatan') }}</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="4">{{ old('catatan') }}</textarea>
                        @error('catatan')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Simpan Laporan') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
