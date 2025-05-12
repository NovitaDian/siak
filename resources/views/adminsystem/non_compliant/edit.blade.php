@extends('layouts.user_type.auth')

@section('content')

<div>

    <h6 class="mb-0 mt-4">{{ __('EDIT DATA PPE FIX') }}</h6>
    <form action="{{ route('adminsystem.non_compliant.update', $ppeFix->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Use PUT for updating data -->
        <input type="hidden" name="id_ppe" value="{{ $ppeFix->id }}">

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Nama HSE Inspector</label>
                    <input type="text" name="nama_hse_inspector" class="form-control" value="{{ $ppeFix->nama_hse_inspector }}" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Shift Kerja</label>
                    <input type="text" name="shift_kerja" class="form-control" value="{{ $ppeFix->shift_kerja }}" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Jam Pengawasan</label>
                    <input type="text" name="jam_pengawasan" class="form-control" value="{{ $ppeFix->jam_pengawasan }}"readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Zona Pengawasan</label>
                    <input type="text" name="zona_pengawasan" class="form-control" value="{{ $ppeFix->zona_pengawasan }}"readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Lokasi Observasi</label>
                    <input type="text" name="lokasi_observasi" class="form-control" value="{{ $ppeFix->lokasi_observasi }}"readonly>
                </div>
            </div>
        </div>


        <h6 class="mb-0 mt-4">{{ __('DATA PELANGGAR') }}</h6>

        <div id="pelanggar-wrapper">
            @if ($nonCompliant) <!-- Check if nonCompliant has data -->
            <div class="row pelanggar-entry">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tipe Observasi</label>
                            <select class="form-control tipe_observasi" name="tipe_observasi" required>
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
                            <div class="form-group">
                                <label for="perusahaan">{{ __('Perusahaan') }}</label>
                                <select class="form-control" id="perusahaan" name="perusahaan" required>
                                    <option value="" disabled>Pilih Perusahaan</option>
                                    @foreach($perusahaans as $perusahaan)
                                    <option value="{{ $perusahaan->perusahaan_name }}" {{ old('perusahaan', $nonCompliant->perusahaan) == $perusahaan->perusahaan_name ? 'selected' : '' }}>
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
                                <select class="form-control" id="nama_bagian" name="nama_bagian" required>
                                    <option value="" disabled>Pilih Bagian</option>
                                    @foreach($bagians as $bagian)
                                    <option value="{{ $bagian->nama_bagian }}" {{ old('nama_bagian', $nonCompliant->nama_bagian) == $bagian->nama_bagian ? 'selected' : '' }}>
                                        {{ $bagian->nama_bagian }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('nama_bagian')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                


                    <div class="col-md-6">
                        <label>Deskripsi Ketidaksesuaian</label>
                        <textarea class="form-control" name="deskripsi_ketidaksesuaian" rows="3">{{ $nonCompliant->deskripsi_ketidaksesuaian }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label>Tindakan</label>
                        <input type="text" name="tindakan" class="form-control" value="{{ $nonCompliant->tindakan }}">
                    </div>
                </div>
            </div>
            @else
            <p>No pelanggar data available.</p>
            @endif
        </div>



        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>

    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#perusahaan').on('change', function() {
            var perusahaan_name = $(this).val();
            if (perusahaan_name) {
                $.ajax({
                    url: '/adminsystem/nonCompliant/get-bagian/' + perusahaan_name,
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