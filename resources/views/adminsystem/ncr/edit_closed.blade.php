@extends('layouts.user_type.auth')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card mx-auto w-100" style="max-width: 95%;">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('EDIT DATA UMUM') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('adminsystem.ncr.update_closed', $ncr_fixs->id) }}" method="POST" enctype="multipart/form-data" role="form text-left">
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

                    <!-- Tanggal Shift Kerja -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_shift_kerja">{{ __('Tanggal Shift Kerja') }}</label>
                                <input class="form-control" type="date" id="tanggal_shift_kerja" name="tanggal_shift_kerja" value="{{ old('tanggal_shift_kerja', $ncr_fixs->tanggal_shift_kerja) }}" readonly required>
                                @error('tanggal_shift_kerja')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Shift Kerja -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shift_kerja">{{ __('Shift Kerja') }}</label>
                                <select class="form-control" id="shift_kerja" name="shift_kerja" readonly required>
                                    <option value="Shift 1" {{ old('shift_kerja', $ncr_fixs->shift_kerja) == 'Shift 1' ? 'selected' : '' }}>SHIFT I</option>
                                    <option value="ZONA II (PROSES, KAPURAN, CT)" {{ old('shift_kerja', $ncr_fixs->shift_kerja) == 'ZONA II (PROSES, KAPURAN, CT)' ? 'selected' : '' }}>SHIFT II</option>
                                    <option value="Shift 3" {{ old('shift_kerja', $ncr_fixs->shift_kerja) == 'Shift 3' ? 'selected' : '' }}>SHIFT III</option>
                                    <option value="Nonshift" {{ old('shift_kerja', $ncr_fixs->shift_kerja) == 'Nonshift' ? 'selected' : '' }}>NONSHIFT</option>
                                </select>
                                @error('shift_kerja')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- H&S Officer 1 / Lead Auditor -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_hs_officer_1">{{ __('Nama H&S Officer 1 / Lead Auditor') }}</label>
                                <input class="form-control" type="text" id="nama_hs_officer_1" name="nama_hs_officer_1" value="{{ old('nama_hs_officer_1', $ncr_fixs->nama_hs_officer_1) }}" readonly required>
                                @error('nama_hs_officer_1')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_hs_officer_2">{{ __('Nama H&S Officer 2 / Auditor') }}</label>
                                <input class="form-control" type="text" id="nama_hs_officer_2" name="nama_hs_officer_2" value="{{ old('nama_hs_officer_2', $ncr_fixs->nama_hs_officer_2) }}" readonly required>
                                @error('nama_hs_officer_2')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_audit">{{ __('Tanggal Audit / Pengawasan') }}</label>
                                <input class="form-control" type="date" id="tanggal_audit" name="tanggal_audit" value="{{ old('tanggal_audit', $ncr_fixs->tanggal_audit) }}" readonly required>
                                @error('tanggal_audit')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Nama Auditee -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_auditee">{{ __('Nama Auditee') }}</label>
                                <input class="form-control" type="text" id="nama_auditee" name="nama_auditee" value="{{ old('nama_auditee', $ncr_fixs->nama_auditee) }}" readonly required>
                                @error('nama_auditee')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Dropdown Perusahaan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="perusahaan">{{ __('Perusahaan') }}</label>
                                <select class="form-control" id="perusahaan" name="perusahaan" readonly required>
                                    <option value="" disabled>Pilih Perusahaan</option>
                                    @foreach($perusahaans as $perusahaan)
                                    <option value="{{ $perusahaan->perusahaan_name }}" {{ old('perusahaan', $ncr_fixs->perusahaan) == $perusahaan->perusahaan_name ? 'selected' : '' }}>
                                        {{ $perusahaan->perusahaan_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('perusahaan')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Dropdown Bagian -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_bagian">{{ __('Bagian') }}</label>
                                <select class="form-control" id="nama_bagian" name="nama_bagian" readonly required>
                                    <option value="" disabled>Pilih Bagian</option>
                                    @foreach($bagians as $bagian)
                                    <option value="{{ $bagian->nama_bagian }}" {{ old('nama_bagian', $ncr_fixs->nama_bagian) == $bagian->nama_bagian ? 'selected' : '' }}>
                                        {{ $bagian->nama_bagian }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('nama_bagian')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <!-- Element / Referensi NCR -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="element_referensi_ncr">{{ __('Element / Referensi NCR') }}</label>
                                <input class="form-control" type="text" id="element_referensi_ncr" name="element_referensi_ncr" value="{{ old('element_referensi_ncr', $ncr_fixs->element_referensi_ncr) }}" readonly required>
                                @error('element_referensi_ncr')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kategori Ketidaksesuaian -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kategori_ketidaksesuaian">{{ __('Kategori Ketidaksesuaian') }}</label>
                                <select class="form-control" id="kategori_ketidaksesuaian" name="kategori_ketidaksesuaian" readonly required>
                                    <option value="System Documentation" {{ old('kategori_ketidaksesuaian', $ncr_fixs->kategori_ketidaksesuaian) == 'System Documentation' ? 'selected' : '' }}>System Documentation</option>
                                    <option value="Implementation/Practices" {{ old ('kategori_ketidaksesuaian', $ncr_fixs->kategori_ketidaksesuaian) == 'Implementation/Practices' ? 'selected' : '' }}>Implementation/Practices</option>
                                    <option value="Review/Analysis" {{ old('kategori_ketidaksesuaian', $ncr_fixs->kategori_ketidaksesuaian) == 'Review/Analysis' ? 'selected' : '' }}>Review/Analysis</option>
                                    <option value="Improvement action" {{ old('kategori_ketidaksesuaian', $ncr_fixs->kategori_ketidaksesuaian) == 'Improvement action' ? 'selected' : '' }}>Improvement action</option>
                                </select>
                                @error('kategori_ketidaksesuaian')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <!-- Deskripsi Ketidaksesuaian -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="deskripsi_ketidaksesuaian">{{ __('Deskripsi Ketidaksesuaian') }}</label>
                                <textarea class="form-control" id="deskripsi_ketidaksesuaian" name="deskripsi_ketidaksesuaian" rows="4" readonly>{{ old('deskripsi_ketidaksesuaian', $ncr_fixs->deskripsi_ketidaksesuaian) }}</textarea>
                                @error('deskripsi_ketidaksesuaian')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tindak_lanjut">{{ __('Tindak Lanjut') }}</label>
                                <textarea class="form-control" id="tindak_lanjut" name="tindak_lanjut" rows="4" readonly>{{ old('tindak_lanjut', $ncr_fixs->tindak_lanjut) }}</textarea>
                                @error('tindak_lanjut')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estimasi">{{ __('Estimasi Penyelesaian') }}</label>
                                <input
                                    class="form-control"
                                    type="date"
                                    id="estimasi"
                                    name="estimasi"
                                    value="{{ old('estimasi', $ncr_fixs->estimasi) }}"
                                    readonly>
                                @error('estimasi')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto">{{ __('Foto') }}</label><br>
                                @if ($ncr_fixs->foto)
                                <img src="{{ asset('storage/' . $ncr_fixs->foto) }}" alt="Foto Sebelumnya" width="200" class="mb-2">
                                @endif
                                <input
                                    class="form-control @error('foto') is-invalid @enderror"
                                    type="file"
                                    id="foto"
                                    name="foto"
                                    accept="image/*"
                                    capture="environment"
                                    readonly>

                                @error('foto')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror

                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="foto_closed">Foto (biarkan kosong jika tidak ingin ubah)</label>
                            <input
                                type="file"
                                name="foto_closed"
                                id="foto_closed"
                                class="form-control"
                                accept="image/*"
                                @if (!$ncr_fixs->foto_closed) required @endif
                            >
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status_note">{{ __('Catatan') }}</label>
                                <textarea class="form-control" id="status_note" name="status_note" rows="4" required>{{ old('status_note', $ncr_fixs->status_note) }}</textarea>
                                @error('status_note')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Update Report') }}</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#perusahaan').on('change', function() {
            var perusahaan_name = $(this).val();
            if (perusahaan_name) {
                $.ajax({
                    url: '/adminsystem/ncr/get-bagian/' + perusahaan_name,
                    type: 'GET',
                    success: function(data) {
                        $('#nama_bagian').empty();
                        $('#nama_bagian').append('<option value="" disabled selected>Pilih Bagian</option>');
                        $.each(data, function(index, bagian) {
                            $('#nama_bagian').append('<option value="' + bagian.nama_bagian + '">' + bagian.nama_bagian + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", error);
                    }
                });
            } else {
                $('#nama_bagian').empty();
            }
        });
    });
</script>

@endsection