@extends('layouts.user_type.auth')

@section('content')

<div>
    <h6 class="mb-0 mt-4">{{ __('EDIT DATA PELANGGAR') }}</h6>
    <form action="{{ route('adminsystem.non_compliant.update', $nonCompliant->id) }}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="id_ppe" value="{{ $nonCompliant->id_ppe }}">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Nama HSE Inspector</label>
                    <input type="text" name="nama_hse_inspector" class="form-control" value="{{ $ppeFix->nama_hse_inspector ?? '' }}" readonly>
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
</div>

<h6 class="mb-0 mt-4">{{ __('DATA PELANGGAR') }}</h6>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Tipe Observasi</label>
            <select class="form-control" name="tipe_observasi" required>
                <option value="" disabled>Pilih Tipe Observasi</option>
                <option value="Unsave behavior" {{ $nonCompliant->tipe_observasi == 'Unsave behavior' ? 'selected' : '' }}>Unsave behavior</option>
                <option value="Unsave action" {{ $nonCompliant->tipe_observasi == 'Unsave action' ? 'selected' : '' }}>Unsave action</option>
                <option value="Unsave condition" {{ $nonCompliant->tipe_observasi == 'Unsave condition' ? 'selected' : '' }}>Unsave condition</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Pelanggar</label>
            <input type="text" name="nama_pelanggar" class="form-control" value="{{ $nonCompliant->nama_pelanggar }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <label for="perusahaan">Perusahaan</label>
        <select class="form-control" id="perusahaan" name="perusahaan" required>
            <option value="" disabled>Pilih Perusahaan</option>
            @foreach($perusahaans as $perusahaan)
            <option value="{{ $perusahaan->perusahaan_name }}" {{ $nonCompliant->perusahaan == $perusahaan->perusahaan_name ? 'selected' : '' }}>
                {{ $perusahaan->perusahaan_name }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="nama_bagian">Bagian</label>
        <select class="form-control" id="nama_bagian" name="nama_bagian" required>
            <option value="{{ $nonCompliant->nama_bagian }}" selected>{{ $nonCompliant->nama_bagian }}</option>
        </select>
    </div>
    <div class="col-md-6">
        <label>Deskripsi Ketidaksesuaian</label>
        <textarea class="form-control" name="deskripsi_ketidaksesuaian" rows="3">{{ $nonCompliant->deskripsi_ketidaksesuaian }}</textarea>
    </div>
    <div class="col-md-6">
        <label>Tindakan</label>
        <input type="text" name="tindakan" class="form-control" value="{{ $nonCompliant->tindakan }}">
    </div>
    <div class="col-md-6">
        <label for="foto">Foto</label>
        <input type="file" class="form-control" name="foto" accept="image/*">
        @if($nonCompliant->foto)
        <p class="mt-2">Foto lama: <br><img src="{{ asset('storage/' . $nonCompliant->foto) }}" alt="Foto" width="150"></p>
        @endif
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Update Report') }}</button>

</div>
</form>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#perusahaan').on('change', function() {
            var perusahaan_name = $(this).val();
            if (perusahaan_name) {
                $.ajax({
                    url: '/adminsystem/non_compliant/get-bagian/' + perusahaan_name,
                    type: 'GET',
                    success: function(data) {
                        $('#nama_bagian').empty().append('<option value="" disabled selected>Pilih Bagian</option>');
                        $.each(data, function(index, bagian) {
                            $('#nama_bagian').append('<option value="' + bagian.nama_bagian + '">' + bagian.nama_bagian + '</option>');
                        });
                    }
                });
            }
        });
    });
</script>
@endsection