@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid ">
        <h2 class="text-black font-weight-bolder text-center">SAFETY BEHAVIOR & PPE COMPLIANCE</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('DATA UMUM') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('adminsystem.ppe.store') }}" method="POST" role="form text-left">
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
                                <label for="tgl_kejadiannya">{{ __('Tanggal Shift Kerja') }}</label>
                                <input class="form-control" type="date" id="tanggal_shift_kerja" name="tanggal_shift_kerja" value="{{ old('tanggal_shift_kerja') }}" required>
                                @error('tanggal_shift_kerja')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shift_kerja">{{ __('Shift Kerja') }}</label>
                                <select class="form-control" id="shift_kerja" name="shift_kerja" required>
                                    <option value="Shift 1" {{ old('shift_kerja') == 'Shift1' ? 'selected' : '' }}>SHIFT I</option>
                                    <option value="ZONA II (PROSES, KAPURAN, CT)" {{ old('shift_kerja') == 'Shift2' ? 'selected' : '' }}>SHIFT II</option>
                                    <option value="Shift 3" {{ old('shift_kerja') == 'Shift3' ? 'selected' : '' }}>SHIFT III</option>
                                    <option value="Nonshift" {{ old('shift_kerja') == 'Nonshift' ? 'selected' : '' }}>NONSHIFT</option>
                                </select>
                                @error('shift_kerja')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_hse_inspector">{{ __('Safety Officer') }}</label>
                                <select class="form-control" id="nama_hse_inspector" name="nama_hse_inspector">
                                    <option value="" disabled selected>Pilih Safety Officer</option>
                                    @foreach($officers as $officer)
                                    <option value="{{ $officer->name }}" {{ old('nama_hse_inspector') == $officer->name ? 'selected' : '' }}>
                                        {{ $officer->name }}
                                    </option>
                                    @endforeach
                                </select>                                @error('nama_hse_inspector')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jam_pengawasan">{{ __('Jam Pengawasan') }}</label>
                                <input class="form-control" type="time" id="jam_pengawasan" name="jam_pengawasan" value="{{ old('jam_pengawasan') }}" required>
                                @error('jam_pengawasan')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="zona_pengawasan">{{ __('Zona Pengawasan') }}</label>
                                <!-- bikin master -->
                                <select class="form-control" id="zona_pengawasan" name="zona_pengawasan" required>
                                    <option value="" {{ old('zona_pengawasan') == '' ? 'selected' : '' }}>Pilih Zona</option>
                                    <option value="ZONA I (OFFICE, SECURITY)" {{ old('zona_pengawasan') == 'ZONA I (OFFICE, SECURITY)' ? 'selected' : '' }}>ZONA I (OFFICE, SECURITY)</option>
                                    <option value="ZONA II (PROSES, KAPURAN, CT)" {{ old('zona_pengawasan') == 'ZONA II (PROSES, KAPURAN, CT)' ? 'selected' : '' }}>ZONA II (PROSES, KAPURAN, CT)</option>
                                    <option value="ZONA III (GD SILO, TIMBANGAN, GD BIRU, LAB)" {{ old('zona_pengawasan') == 'ZONA III (GD SILO, TIMBANGAN, GD BIRU, LAB)' ? 'selected' : '' }}>ZONA III (GD SILO, TIMBANGAN, GD BIRU, LAB)</option>
                                    <option value="ZONA IV (DEMIN, TURBIN, BOILER)" {{ old('zona_pengawasan') == 'ZONA IV (DEMIN, TURBIN, BOILER)' ? 'selected' : '' }}>ZONA IV (DEMIN, TURBIN, BOILER)</option>
                                    <option value="ZONA V (IPAL, WORKSHOP, MWH)" {{ old('zona_pengawasan') == 'ZONA V (IPAL, WORKSHOP, MWH)' ? 'selected' : '' }}>ZONA V (IPAL, WORKSHOP, MWH)</option>
                                </select>
                                @error('shift_kerja')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="lokasi_observasi">{{ __('Lokasi Observasi') }}</label>
                                <input class="form-control" type="text" id="lokasi_observasi" name="lokasi_observasi" value="{{ old('lokasi_observasi') }}" required>
                                @error('lokasi_observasi')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <h6 class="mb-0">{{ __('JUMLAH PATUH APD') }}</h6>

                        <!-- Jumlah Patuh APD -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>{{ __('Jumlah Patuh APD') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" type="number" id="jumlah_patuh_apd_karyawan" name="jumlah_patuh_apd_karyawan" min="0" placeholder="Karyawan" required>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" type="number" id="jumlah_patuh_apd_kontraktor" name="jumlah_patuh_apd_kontraktor" min="0" placeholder="Kontraktor" required>
                            </div>
                        </div>

                        <!-- Jumlah Tidak Patuh Helm -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>{{ __('Jumlah Tidak Patuh Helm') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" type="number" id="jumlah_tidak_patuh_helm_karyawan" name="jumlah_tidak_patuh_helm_karyawan" min="0" placeholder="Karyawan" required>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" type="number" id="jumlah_tidak_patuh_helm_kontraktor" name="jumlah_tidak_patuh_helm_kontraktor" min="0" placeholder="Kontraktor" required>
                            </div>
                        </div>

                        <!-- Jumlah Tidak Patuh Sepatu -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>{{ __('Jumlah Tidak Patuh Sepatu') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" type="number" id="jumlah_tidak_patuh_sepatu_karyawan" name="jumlah_tidak_patuh_sepatu_karyawan" min="0" placeholder="Karyawan" required>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" type="number" id="jumlah_tidak_patuh_sepatu_kontraktor" name="jumlah_tidak_patuh_sepatu_kontraktor" min="0" placeholder="Kontraktor" required>
                            </div>
                        </div>

                        <!-- Jumlah Tidak Patuh Pelindung Mata -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>{{ __('Jumlah Tidak Patuh Pelindung Mata') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" type="number" id="jumlah_tidak_patuh_pelindung_mata_karyawan" name="jumlah_tidak_patuh_pelindung_mata_karyawan" min="0" placeholder="Karyawan" required>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" type="number" id="jumlah_tidak_patuh_pelindung_mata_kontraktor" name="jumlah_tidak_patuh_pelindung_mata_kontraktor" min="0" placeholder="Kontraktor" required>
                            </div>
                        </div>

                        <!-- Jumlah Tidak Patuh Safety Harness -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>{{ __('Jumlah Tidak Patuh Safety Harness') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" type="number" id="jumlah_tidak_patuh_safety_harness_karyawan" name="jumlah_tidak_patuh_safety_harness_karyawan" min="0" placeholder="Karyawan" required>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" type="number" id="jumlah_tidak_patuh_safety_harness_kontraktor" name="jumlah_tidak_patuh_safety_harness_kontraktor" min="0" placeholder="Kontraktor" required>
                            </div>
                        </div>

                        <!-- Jumlah Tidak Patuh APD Lainnya -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>{{ __('Jumlah Tidak Patuh APD Lainnya') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" type="number" id="jumlah_tidak_patuh_apd_lainnya_karyawan" name="jumlah_tidak_patuh_apd_lainnya_karyawan" min="0" placeholder="Karyawan" required>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" type="number" id="jumlah_tidak_patuh_apd_lainnya_kontraktor" name="jumlah_tidak_patuh_apd_lainnya_kontraktor" min="0" placeholder="Kontraktor" required>
                            </div>
                        </div>

                        <!-- Keterangan Tidak Patuh -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>{{ __('Keterangan Tidak Patuh') }}</label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control" id="keterangan_tidak_patuh" name="keterangan_tidak_patuh" rows="3"></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Save Report') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection