@extends('layouts.user_type.auth')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row"></div>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center pb-0">
                <h6 class="mb-0">REPORT NON COMPLIANT</h6>

            </div>

            <div class="card-body px-4 pt-4 pb-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Tanggal Shift Kerja:</strong><br>
                        {{ \Carbon\Carbon::parse($ppeFix->tanggal_shift_kerja)->format('d/m/Y') }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Shift Kerja:</strong><br>
                        {{ $ppeFix->shift_kerja }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Safety Officer:</strong><br>
                        {{ $ppeFix->nama_hse_inspector }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Jam Pengawasan:</strong><br>
                        {{ $ppeFix->jam_pengawasan }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Zona Pengawasan:</strong><br>
                        {{ $ppeFix->zona_pengawasan }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Lokasi Observasi:</strong><br>
                        {{ $ppeFix->lokasi_observasi }}
                    </div>
                </div>
            </div>


            <div class="card-header d-flex justify-content-between align-items-center pb-0">
                <h6 class="mb-0"></h6>
                <form action="{{ route('adminsystem.non_compliant.create', $ppeFix->id) }}" method="GET" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0" id="dataTableDraft">
                    <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Pelanggar</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deskripsi Ketidaksesuaian</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Perusahaan</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Bagian</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tindakan</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="draftTableBody">
                        @forelse ($nonCompliants as $nc)
                        <tr>
                            <td class="text-center text-xs">{{ $loop->iteration }}</td>
                            <td class="text-center text-xs">{{ $nc->nama_pelanggar }}</td>
                            <td class="text-center text-xs">{{ $nc->deskripsi_ketidaksesuaian }}</td>
                            <td class="text-center text-xs">{{ $nc->perusahaan }}</td>
                            <td class="text-center text-xs">{{ $nc->nama_bagian }}</td>
                            <td class="text-center text-xs">{{ $nc->tindakan }}</td>
                            <td class="align-middle text-center">
                                <div class="d-flex justify-content-center">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('adminsystem.non_compliant.edit', $nc->id) }}" class="btn btn-warning btn-xs me-2">
                                        <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit
                                    </a>
                                    <!-- Tombol Delete -->
                                    <form action="{{ route('adminsystem.non_compliant.destroy', $nc->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-danger btn-xs"
                                            onclick="return confirm('Anda yakin akan menghapus data ini?')"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt me-1" style="font-size: 12px;"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="text-center text-muted">Data tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</main>
@endsection