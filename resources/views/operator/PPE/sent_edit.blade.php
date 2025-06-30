@extends('layouts.user_type.operator')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">EDIT SAFETY BEHAVIOR & PPE COMPLIANCE</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%;">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('EDIT DATA UMUM') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('operator.ppe.sent_update', $ppeFixs->id) }}" method="POST" role="form text-left">
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

                    @if(session('success'))
                        <div class="m-3 alert alert-success alert-dismissible fade show" role="alert">
                            <span class="alert-text text-white">{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif

                    <!-- Tanggal & Shift -->
                    <div class="row">
                        <div class="col-md-6">
                            <label for="tanggal_shift_kerja">Tanggal Shift Kerja</label>
                            <input type="date" class="form-control" id="tanggal_shift_kerja" name="tanggal_shift_kerja" value="{{ old('tanggal_shift_kerja', $ppeFixs->tanggal_shift_kerja) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="shift_kerja">Shift Kerja</label>
                            <select class="form-control" id="shift_kerja" name="shift_kerja" required>
                                <option value="Shift 1" {{ old('shift_kerja', $ppeFixs->shift_kerja) == 'Shift 1' ? 'selected' : '' }}>SHIFT I</option>
                                <option value="Shift 2" {{ old('shift_kerja', $ppeFixs->shift_kerja) == 'Shift 2' ? 'selected' : '' }}>SHIFT II</option>
                                <option value="Shift 3" {{ old('shift_kerja', $ppeFixs->shift_kerja) == 'Shift 3' ? 'selected' : '' }}>SHIFT III</option>
                                <option value="Nonshift" {{ old('shift_kerja', $ppeFixs->shift_kerja) == 'Nonshift' ? 'selected' : '' }}>NONSHIFT</option>
                            </select>
                        </div>
                    </div>

                    <!-- HSE Inspector, Jam Mulai/Selesai -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="hse_inspector_id">HSE Inspector</label>
                            <select class="form-control" name="hse_inspector_id" id="hse_inspector_id" required>
                                <option value="">Pilih HSE Inspector</option>
                                @foreach($inspectors as $inspector)
                                    <option value="{{ $inspector->id }}" {{ old('hse_inspector_id', $ppeFixs->hse_inspector_id) == $inspector->id ? 'selected' : '' }}>{{ $inspector->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="jam_mulai">Jam Mulai</label>
                            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', $ppeFixs->jam_mulai) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="jam_selesai">Jam Selesai</label>
                            <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', $ppeFixs->jam_selesai) }}" required>
                        </div>
                    </div>

                    <!-- Zona & Lokasi -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="zona_pengawasan">Zona Pengawasan</label>
                            <select class="form-control" id="zona_pengawasan" name="zona_pengawasan" required>
                                @foreach([
                                    'ZONA I (OFFICE, SECURITY)',
                                    'ZONA II (PROSES, KAPURAN, CT)',
                                    'ZONA III (GD SILO, TIMBANGAN, GD BIRU, LAB)',
                                    'ZONA IV (DEMIN, TURBIN, BOILER)',
                                    'ZONA V (IPAL, WORKSHOP, MWH)'
                                ] as $zona)
                                    <option value="{{ $zona }}" {{ old('zona_pengawasan', $ppeFixs->zona_pengawasan) == $zona ? 'selected' : '' }}>{{ $zona }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="lokasi_observasi">Lokasi Observasi</label>
                            <input type="text" class="form-control" id="lokasi_observasi" name="lokasi_observasi" value="{{ old('lokasi_observasi', $ppeFixs->lokasi_observasi) }}" required>
                        </div>
                    </div>

                    <!-- Bagian Patuh dan Tidak Patuh -->
                    @php
                        $fields = [
                            'jumlah_patuh_apd',
                            'jumlah_tidak_patuh_helm',
                            'jumlah_tidak_patuh_sepatu',
                            'jumlah_tidak_patuh_pelindung_mata',
                            'jumlah_tidak_patuh_safety_harness',
                            'jumlah_tidak_patuh_apd_lainnya',
                        ];
                    @endphp

                    @foreach($fields as $field)
                        @php
                            $label = ucwords(str_replace('_', ' ', $field));
                        @endphp
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>{{ __($label) }}</label>
                            </div>
                            <div class="col-md-4">
                                <input type="number" class="form-control" name="{{ $field }}_karyawan" min="0" value="{{ old("{$field}_karyawan", $ppeFixs->{"{$field}_karyawan"}) }}" placeholder="Karyawan">
                            </div>
                            <div class="col-md-4">
                                <input type="number" class="form-control" name="{{ $field }}_kontraktor" min="0" value="{{ old("{$field}_kontraktor", $ppeFixs->{"{$field}_kontraktor"}) }}" placeholder="Kontraktor">
                            </div>
                        </div>
                    @endforeach

                    <!-- Keterangan -->
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="keterangan_tidak_patuh">Keterangan Tidak Patuh</label>
                        </div>
                        <div class="col-md-8">
                            <textarea class="form-control" id="keterangan_tidak_patuh" name="keterangan_tidak_patuh" rows="3">{{ old('keterangan_tidak_patuh', $ppeFixs->keterangan_tidak_patuh) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Update Report') }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
