@extends('layouts.user_type.auth')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div class="container-fluid">
    <h2 class="text-black font-weight-bolder text-center">EDIT NONCONFORMITY REPORT</h2>
    <div class="card mx-auto w-100" style="max-width: 150%;">
        <div class="card-header pb-0 px-3">
            <h6 class="mb-0">{{ __('EDIT DATA UMUM') }}</h6>
        </div>
        <div class="card-body pt-4 p-3">
            <form action="{{ route('adminsystem.ncr.sent_update', $ncr_fixs->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row">
                    <!-- Hidden field for perusahaan_id -->
                    <input type="hidden" name="perusahaan_id" id="perusahaan_id" value="{{ $ncr_fixs->perusahaan_id }}">

                    <div class="col-md-6">
                        <label for="tanggal_shift_kerja">Tanggal Shift Kerja</label>
                        <input type="date" class="form-control" name="tanggal_shift_kerja" value="{{ old('tanggal_shift_kerja', $ncr_fixs->tanggal_shift_kerja) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="shift_kerja">Shift Kerja</label>
                        <select name="shift_kerja" class="form-control" required>
                            <option value="Shift 1" {{ $ncr_fixs->shift_kerja == 'Shift 1' ? 'selected' : '' }}>Shift 1</option>
                            <option value="Shift 2" {{ $ncr_fixs->shift_kerja == 'Shift 2' ? 'selected' : '' }}>Shift 2</option>
                            <option value="Shift 3" {{ $ncr_fixs->shift_kerja == 'Shift 3' ? 'selected' : '' }}>Shift 3</option>
                            <option value="Nonshift" {{ $ncr_fixs->shift_kerja == 'Nonshift' ? 'selected' : '' }}>Nonshift</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="nama_hs_officer_1">Nama H&S Officer 1</label>
                        <input type="text" class="form-control" name="nama_hs_officer_1" value="{{ old('nama_hs_officer_1', $ncr_fixs->nama_hs_officer_1) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="nama_hs_officer_2">Nama H&S Officer 2</label>
                        <input type="text" class="form-control" name="nama_hs_officer_2" value="{{ old('nama_hs_officer_2', $ncr_fixs->nama_hs_officer_2) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal_audit">Tanggal Audit</label>
                        <input type="date" class="form-control" name="tanggal_audit" value="{{ old('tanggal_audit', $ncr_fixs->tanggal_audit) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="nama_auditee">Nama Auditee</label>
                        <input type="text" class="form-control" name="nama_auditee" value="{{ old('nama_auditee', $ncr_fixs->nama_auditee) }}" required>
                    </div>

                    <!-- Perusahaan (pakai perusahaan_id) -->
                    <div class="col-md-6">
                        <label for="perusahaan_id">Perusahaan</label>
                        <select name="perusahaan_id" id="perusahaan_id" class="form-control" required>
                            <option value="" disabled>Pilih Perusahaan</option>
                            @foreach($perusahaans as $perusahaan)
                            <option value="{{ $perusahaan->id }}" {{ $ncr_fixs->perusahaan_id == $perusahaan->id ? 'selected' : '' }}>
                                {{ $perusahaan->perusahaan_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="nama_bagian">Bagian</label>
                        <select name="nama_bagian" id="nama_bagian" class="form-control" required>
                            <option value="{{ $ncr_fixs->nama_bagian }}" selected>{{ $ncr_fixs->nama_bagian }}</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="element_referensi_ncr">Element / Referensi NCR</label>
                        <input type="text" class="form-control" name="element_referensi_ncr" value="{{ old('element_referensi_ncr', $ncr_fixs->element_referensi_ncr) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="kategori_ketidaksesuaian">Kategori Ketidaksesuaian</label>
                        <select class="form-control" name="kategori_ketidaksesuaian" required>
                            <option value="System Documentation" {{ $ncr_fixs->kategori_ketidaksesuaian == 'System Documentation' ? 'selected' : '' }}>System Documentation</option>
                            <option value="Implementation/Practices" {{ $ncr_fixs->kategori_ketidaksesuaian == 'Implementation/Practices' ? 'selected' : '' }}>Implementation/Practices</option>
                            <option value="Review/Analysis" {{ $ncr_fixs->kategori_ketidaksesuaian == 'Review/Analysis' ? 'selected' : '' }}>Review/Analysis</option>
                            <option value="Improvement action" {{ $ncr_fixs->kategori_ketidaksesuaian == 'Improvement action' ? 'selected' : '' }}>Improvement action</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="deskripsi_ketidaksesuaian">Deskripsi Ketidaksesuaian</label>
                        <textarea class="form-control" name="deskripsi_ketidaksesuaian" rows="4">{{ old('deskripsi_ketidaksesuaian', $ncr_fixs->deskripsi_ketidaksesuaian) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="tindak_lanjut">Tindak Lanjut</label>
                        <textarea class="form-control" name="tindak_lanjut" rows="4">{{ old('tindak_lanjut', $ncr_fixs->tindak_lanjut) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="estimasi">Estimasi Penyelesaian</label>
                        <input type="date" class="form-control" name="estimasi" value="{{ old('estimasi', $ncr_fixs->estimasi) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="foto">Foto (Kosongkan jika tidak mengubah)</label>
                        <input type="file" class="form-control" name="foto" accept="image/*">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn bg-gradient-dark">Update Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#perusahaan_id').on('change', function() {
            var code = $(this).val();
            if (code) {
                $.ajax({
                    url: '/adminsystem/master/perusahaan/get-bagian/' + code,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#nama_bagian').empty().append('<option value="" disabled selected>Pilih Bagian</option>');
                        $.each(data, function(index, bagian) {
                            $('#nama_bagian').append('<option value="' + bagian.nama_bagian + '">' + bagian.nama_bagian + '</option>');
                        });
                    },
                    error: function() {
                        alert('Gagal memuat data bagian.');
                    }
                });

                // Ambil ID perusahaan juga via AJAX (agar update perusahaan_id di hidden input)
                $.ajax({
                    url: '/api/get-perusahaan-id/' + code,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#perusahaan_id').val(data.id);
                    }
                });
            }
        });
    });
</script>
@endsection