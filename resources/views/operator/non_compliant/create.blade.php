@extends('layouts.user_type.operator')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div>

    <h6 class="mb-0 mt-4">{{ __('DATA PELANGGAR') }}</h6>
    <form action="{{ route('operator.non_compliant.store') }}" method="POST" enctype="multipart/form-data">
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
        <input type="hidden" name="id_ppe" value="{{ $ppeFix->id }}">

        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Nama HSE Inspector</label>
                    <input type="text" name="hse_inspector_id" class="form-control" value="{{ $ppeFix->inspector->name ?? '' }}" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Shift Kerja</label>
                    <input type="text" name="shift_kerja" class="form-control" value="{{ $ppeFix->shift_kerja ?? '' }}" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Jam Mulai</label>
                    <input type="text" name="jam_mulai" class="form-control" value="{{ $ppeFix->jam_mulai ?? '' }}" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Jam Selesai</label>
                    <input type="text" name="jam_selesai" class="form-control" value="{{ $ppeFix->jam_selesai ?? '' }}" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Zona Pengawasan</label>
                    <input type="text" name="zona_pengawasan" class="form-control" value="{{ $ppeFix->zona_pengawasan ?? '' }}" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Lokasi Observasi</label>
                    <input type="text" name="lokasi_observasi" class="form-control" value="{{ $ppeFix->lokasi_observasi ?? '' }}" readonly>
                </div>
            </div>
        </div>

        <h6 class="mb-0 mt-4">{{ __('DATA PELANGGAR') }}</h6>

        <!-- Form Start -->

        <div id="pelanggar-wrapper">
            <div class="row pelanggar-entry">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tipe Observasi</label>
                            <select class="form-control tipe_observasi" name="tipe_observasi" required>
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
                            <input type="text" name="nama_pelanggar" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="perusahaan_id">Perusahaan</label>
                            <select class="form-control" id="perusahaan_id" name="perusahaan_id">
                                <option value="" disabled selected>Pilih Perusahaan</option>
                                @foreach($perusahaans as $perusahaan)
                                <option value="{{ $perusahaan->id }}" {{ old('perusahaan_id') == $perusahaan->id ? 'selected' : '' }}>
                                    {{ $perusahaan->perusahaan_name }}
                                </option>
                                @endforeach
                            </select>
                            @error('perusahaan_id')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dropdown Bagian -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_bagian">{{ __('Bagian') }}</label>
                            <select class="form-control" id="nama_bagian" name="nama_bagian" required>
                                <option value="" disabled selected>Pilih Bagian</option>
                            </select>
                            @error('nama_bagian')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Deskripsi Ketidaksesuaian</label>
                        <textarea class="form-control" name="deskripsi_ketidaksesuaian" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label>Tindakan</label>
                        <input type="text" name="tindakan" class="form-control">
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
                </div>
            </div>
        </div>



        <!-- Tombol Submit -->
        <div class="mt-4">
            <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Save Report') }}</button>

        </div>
    </form>
</div>
<!-- Tambahkan jQuery (atau menggunakan CDN jika belum ada) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#perusahaan_id').on('change', function() {
            var code = $(this).val();
            if (code) {
                $.ajax({
                    url: '/operator/master/perusahaan/get-bagian/' + code,
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