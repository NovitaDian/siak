@extends('layouts.user_type.auth')

@section('content')

<div>

    <h6 class="mb-0 mt-4">{{ __('DATA PPE FIX') }}</h6>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Nama HSE Inspector</label>
                <input type="text" class="form-control" value="{{ $ppeFix->nama_hse_inspector ?? '' }}" readonly>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label>Shift Kerja</label>
                <input type="text" class="form-control" value="{{ $ppeFix->shift_kerja ?? '' }}" readonly>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label>Jam Pengawasan</label>
                <input type="text" class="form-control" value="{{ $ppeFix->jam_pengawasan ?? '' }}" readonly>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label>Zona Pengawasan</label>
                <input type="text" class="form-control" value="{{ $ppeFix->zona_pengawasan ?? '' }}" readonly>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label>Lokasi Observasi</label>
                <input type="text" class="form-control" value="{{ $ppeFix->lokasi_observasi ?? '' }}" readonly>
            </div>
        </div>
    </div>

    <h6 class="mb-0 mt-4">{{ __('DATA PELANGGAR') }}</h6>

    <!-- Form Start -->
    <form action="{{ route('adminsystem.non_compliant.store') }}" method="POST">
        @csrf

        <div id="pelanggar-wrapper">
            <!-- Template Pelanggar Pertama -->
            <div class="row pelanggar-entry">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tipe Observasi</label>
                            <select class="form-control tipe_observasi" name="tipe_observasi[]" required>
                                <option value="" disabled selected>Pilih Tipe Observasi</option>
                                <option value="Unsave behavior">Unsave behavior</option>
                                <option value="Unsave action">Unsave action</option>
                                <option value="Unsave condition">Unsave condition</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Pelanggar</label>
                            <input type="text" name="nama_pelanggar[]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Perusahaan</label>
                        <select class="form-control perusahaan" name="perusahaan[]" required>
                            <option value="" disabled selected>Pilih Perusahaan</option>
                            @foreach($perusahaans as $perusahaan)
                            <option value="{{ $perusahaan->perusahaan_name }}">{{ $perusahaan->perusahaan_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Bagian</label>
                        <select class="form-control nama_bagian" name="nama_bagian[]" required>
                            <option value="" disabled selected>Pilih Bagian</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Deskripsi Ketidaksesuaian</label>
                        <textarea class="form-control" name="deskripsi_ketidaksesuaian[]" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label>Tindakan</label>
                        <input type="text" name="tindakan[]" class="form-control">
                    </div>

                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-pelanggar">X</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Tambah -->
        <div class="mt-3">
            <button type="button" class="btn btn-success btn-sm" id="addPelanggar">+ Tambah Pelanggar</button>
        </div>

        <!-- Tombol Submit -->
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Submit Semua Data Pelanggar</button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#addPelanggar').click(function() {
            console.log("Tombol Tambah Pelanggar Ditekan");
            var clone = $('.pelanggar-entry').first().clone();
            clone.find('input, textarea').val('');
            clone.find('select').val('');
            $('#pelanggar-wrapper').append(clone);
        });

        // Hapus pelanggar
        $(document).on('click', '.remove-pelanggar', function() {
            if ($('.pelanggar-entry').length > 1) {
                $(this).closest('.pelanggar-entry').remove();
            } else {
                alert('Minimal 1 pelanggar harus ada.');
            }
        });

        // Dynamic isi bagian berdasarkan perusahaan
        $(document).on('change', '.perusahaan', function() {
            var perusahaan = $(this).val();
            var bagianSelect = $(this).closest('.pelanggar-entry').find('.nama_bagian');

            if (perusahaan) {
                $.ajax({
                    url: '/adminsystem/non_compliant/get-bagian/' + perusahaan,
                    type: 'GET',
                    success: function(data) {
                        bagianSelect.empty().append('<option value="" disabled selected>Pilih Bagian</option>');
                        $.each(data, function(index, bagian) {
                            bagianSelect.append('<option value="' + bagian.nama_bagian + '">' + bagian.nama_bagian + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", error);
                    }
                });
            } else {
                bagianSelect.empty().append('<option value="" disabled selected>Pilih Bagian</option>');
            }
        });
    });
</script>
@endpush 