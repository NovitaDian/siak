@extends('layouts.user_type.operator')

@section('content')

<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">EDIT NONCONFORMITY REPORT</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%;">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('EDIT DATA UMUM') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('operator.ncr.update', $ncr->id) }}" method="POST" enctype="multipart/form-data" role="form text-left">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <label for="tanggal_shift_kerja">Tanggal Shift Kerja</label>
                            <input type="date" class="form-control" name="tanggal_shift_kerja" id="tanggal_shift_kerja" value="{{ old('tanggal_shift_kerja', $ncr->tanggal_shift_kerja) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="shift_kerja">Shift Kerja</label>
                            <select name="shift_kerja" id="shift_kerja" class="form-control" required>
                                <option value="Shift 1" {{ $ncr->shift_kerja == 'Shift 1' ? 'selected' : '' }}>Shift 1</option>
                                <option value="Shift 2" {{ $ncr->shift_kerja == 'Shift 2' ? 'selected' : '' }}>Shift 2</option>
                                <option value="Shift 3" {{ $ncr->shift_kerja == 'Shift 3' ? 'selected' : '' }}>Shift 3</option>
                                <option value="Nonshift" {{ $ncr->shift_kerja == 'Nonshift' ? 'selected' : '' }}>Nonshift</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="nama_hs_officer_1">Nama H&S Officer 1</label>
                            <input type="text" name="nama_hs_officer_1" id="nama_hs_officer_1" class="form-control" value="{{ old('nama_hs_officer_1', $ncr->nama_hs_officer_1) }}">
                        </div>

                        <div class="col-md-6">
                            <label for="nama_hs_officer_2">Nama H&S Officer 2</label>
                            <input type="text" name="nama_hs_officer_2" id="nama_hs_officer_2" class="form-control" value="{{ old('nama_hs_officer_2', $ncr->nama_hs_officer_2) }}">
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal_audit">Tanggal Audit</label>
                            <input type="date" name="tanggal_audit" id="tanggal_audit" class="form-control" value="{{ old('tanggal_audit', $ncr->tanggal_audit) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="nama_auditee">Nama Auditee</label>
                            <input type="text" name="nama_auditee" id="nama_auditee" class="form-control" value="{{ old('nama_auditee', $ncr->nama_auditee) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="perusahaan">Perusahaan</label>
                            <select name="perusahaan" id="perusahaan" class="form-control" required>
                                <option value="" disabled selected>Pilih Perusahaan</option>
                                @foreach($perusahaans as $perusahaan)
                                    <option value="{{ $perusahaan->perusahaan_name }}" {{ $ncr->perusahaan == $perusahaan->perusahaan_name ? 'selected' : '' }}>
                                        {{ $perusahaan->perusahaan_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="nama_bagian">Bagian</label>
                            <select name="nama_bagian" id="nama_bagian" class="form-control" required>
                                <option value="{{ $ncr->nama_bagian }}" selected>{{ $ncr->nama_bagian }}</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="element_referensi_ncr">Element / Referensi NCR</label>
                            <input type="text" name="element_referensi_ncr" id="element_referensi_ncr" class="form-control" value="{{ old('element_referensi_ncr', $ncr->element_referensi_ncr) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="kategori_ketidaksesuaian">Kategori Ketidaksesuaian</label>
                            <select name="kategori_ketidaksesuaian" id="kategori_ketidaksesuaian" class="form-control" required>
                                <option value="System Documentation" {{ $ncr->kategori_ketidaksesuaian == 'System Documentation' ? 'selected' : '' }}>System Documentation</option>
                                <option value="Implementation/Practices" {{ $ncr->kategori_ketidaksesuaian == 'Implementation/Practices' ? 'selected' : '' }}>Implementation/Practices</option>
                                <option value="Review/Analysis" {{ $ncr->kategori_ketidaksesuaian == 'Review/Analysis' ? 'selected' : '' }}>Review/Analysis</option>
                                <option value="Improvement action" {{ $ncr->kategori_ketidaksesuaian == 'Improvement action' ? 'selected' : '' }}>Improvement action</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="deskripsi_ketidaksesuaian">Deskripsi Ketidaksesuaian</label>
                            <textarea name="deskripsi_ketidaksesuaian" id="deskripsi_ketidaksesuaian" class="form-control" rows="4">{{ old('deskripsi_ketidaksesuaian', $ncr->deskripsi_ketidaksesuaian) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="tindak_lanjut">Tindak Lanjut</label>
                            <textarea name="tindak_lanjut" id="tindak_lanjut" class="form-control" rows="4">{{ old('tindak_lanjut', $ncr->tindak_lanjut) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="estimasi">Estimasi Penyelesaian</label>
                            <input type="date" name="estimasi" id="estimasi" class="form-control" value="{{ old('estimasi', $ncr->estimasi) }}">
                        </div>

                        <div class="col-md-6">
                            <label for="foto">Foto (biarkan kosong jika tidak ingin ubah)</label>
                            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
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
            var perusahaan = $(this).val();
            if (perusahaan) {
                $.ajax({
                    url: '/operator/ncr/get-bagian/' + perusahaan,
                    type: 'GET',
                    success: function(data) {
                        $('#nama_bagian').empty().append('<option value="" disabled selected>Pilih Bagian</option>');
                        $.each(data, function(index, bagian) {
                            $('#nama_bagian').append('<option value="' + bagian.nama_bagian + '">' + bagian.nama_bagian + '</option>');
                        });
                    },
                    error: function(xhr) {
                        console.log("Error:", xhr);
                    }
                });
            }
        });
    });
</script>

@endsection
