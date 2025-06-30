@extends('layouts.user_type.operator')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">NONCONFORMITY REPORT AND CORRECTIVE/PREVENTIVE ACTION REQUEST</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('DATA UMUM') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('operator.ncr.store') }}" method="POST" enctype="multipart/form-data" role="form text-left">
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

                    <!-- Tanggal Shift Kerja -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_shift_kerja">{{ __('Tanggal Shift Kerja') }}</label>
                                <input class="form-control" type="date" id="tanggal_shift_kerja" name="tanggal_shift_kerja" value="{{ old('tanggal_shift_kerja') }}" required>
                                @error('tanggal_shift_kerja')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Shift Kerja -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shift_kerja">{{ __('Shift Kerja') }}</label>
                                <select class="form-control" id="shift_kerja" name="shift_kerja" required>
                                    <option value="Shift 1" {{ old('shift_kerja') == 'Shift 1' ? 'selected' : '' }}>SHIFT I</option>
                                    <option value="Shift 2" {{ old('shift_kerja') == 'Shift 2' ? 'selected' : '' }}>SHIFT II</option>
                                    <option value="Shift 3" {{ old('shift_kerja') == 'Shift 3' ? 'selected' : '' }}>SHIFT III</option>
                                    <option value="Nonshift" {{ old('shift_kerja') == 'Nonshift' ? 'selected' : '' }}>NONSHIFT</option>
                                </select>
                                @error('shift_kerja')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_hs_officer_1">{{ __('Nama H&S Officer 1 / Lead Auditor') }}</label>
                                <input class="form-control" type="text" id="nama_hs_officer_1" name="nama_hs_officer_1" value="{{ old('nama_hs_officer_1') }}">
                                @error('nama_hs_officer_1')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_hs_officer_2">{{ __('Nama H&S Officer 2 / Auditor') }}</label>
                                <input class="form-control" type="text" id="nama_hs_officer_2" name="nama_hs_officer_2" value="{{ old('nama_hs_officer_2') }}">
                                @error('nama_hs_officer_2')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <!-- Tanggal Audit / Pengawasan -->

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_audit">{{ __('Tanggal Audit / Pengawasan') }}</label>
                                <input class="form-control" type="date" id="tanggal_audit" name="tanggal_audit" value="{{ old('tanggal_audit') }}" required>
                                @error('tanggal_audit')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Nama Auditee -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_auditee">{{ __('Nama Auditee/Owner Area') }}</label>
                                <input class="form-control" type="text" id="nama_auditee" name="nama_auditee" value="{{ old('nama_auditee') }}" required>
                                @error('nama_auditee')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <!-- Dropdown Perusahaan -->

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="perusahaan">{{ __('Perusahaan') }}</label>
                                <select class="form-control" id="perusahaan" name="perusahaan" required>
                                    <option value="" disabled selected>Pilih Perusahaan</option>
                                    @foreach($perusahaans as $perusahaan)
                                    <option value="{{ $perusahaan->perusahaan_name }}" {{ old('perusahaan') == $perusahaan->perusahaan_name ? 'selected' : '' }}>
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
                                <label for="nama_bagian">{{ __('Bagian/Department') }}</label>
                                <select class="form-control" id="nama_bagian" name="nama_bagian" required>
                                    <option value="" disabled selected>Pilih Bagian</option>
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
                                <input class="form-control" type="text" id="element_referensi_ncr" name="element_referensi_ncr" value="{{ old('element_referensi_ncr') }}" required>
                                @error('element_referensi_ncr')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kategori_ketidaksesuaian">{{ __('Kategori Ketidaksesuaian') }}</label>
                                <select class="form-control" id="kategori_ketidaksesuaian" name="kategori_ketidaksesuaian" required>
                                    <option value="System Documentation" {{ old('kategori_ketidaksesuaian') == 'System Documentation' ? 'selected' : '' }}>System Documentation</option>
                                    <option value="Implementation/Practices" {{ old('kategori_ketidaksesuaian') == 'Implementation/Practices' ? 'selected' : '' }}>Implementation/Practices</option>
                                    <option value="Review/Analysis" {{ old('kategori_ketidaksesuaian') == 'Review/Analysis' ? 'selected' : '' }}>Review/Analysis</option>
                                    <option value="Improvement action" {{ old('kategori_ketidaksesuaian') == 'Improvement action' ? 'selected' : '' }}>Improvement action</option>
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
                                <textarea class="form-control" id="deskripsi_ketidaksesuaian" name="deskripsi_ketidaksesuaian" rows="4" required>{{ old('deskripsi_ketidaksesuaian') }}</textarea>
                                @error('deskripsi_ketidaksesuaian')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tindak_lanjut">{{ __('Tindak Lanjut') }}</label>
                                <textarea class="form-control" id="tindak_lanjut" name="tindak_lanjut" rows="4" required>{{ old('tindak_lanjut') }}</textarea>
                                @error('tindak_lanjut')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estimasi">{{ __('Estimasi Penyelesaian') }}</label>
                                <input class="form-control" type="date" id="estimasi" name="estimasi" value="{{ old('estimasi') }}" required>
                                @error('estimasi')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto">{{ __('Foto') }}</label>
                                <input
                                    class="form-control @error('foto') is-invalid @enderror"
                                    type="file"
                                    id="foto"
                                    name="foto"
                                    accept="image/*"

                                    required>
                                @error('foto')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
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

<!-- Tambahkan jQuery (atau menggunakan CDN jika belum ada) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#perusahaan').on('change', function() {
            var perusahaan_name = $(this).val();
            console.log("Selected Perusahaan: ", perusahaan_name); // Log the selected value

            if (perusahaan_name) {
                $.ajax({
                    url: '/operator/ncr/get-bagian/' + perusahaan_name,
                    type: 'GET',
                    success: function(data) {
                        console.log("Bagian Data: ", data); // Log the response data
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