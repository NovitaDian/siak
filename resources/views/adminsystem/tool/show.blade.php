@extends('layouts.user_type.auth')

@section('content')
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin: 10px;">
    {{ session('success') }}
</div>
@endif
<div class="container-fluid">
    <h2 class="text-black font-weight-bolder text-center mb-4">DETAIL LAPORAN PENGECEKAN ALAT</h2>

    <div class="card mx-auto w-100" style="max-width: 800px;">
        <div class="card-header pb-0 px-3">
            <h6 class="mb-0">{{ __('Informasi Pemeriksaan') ?? '-'}}</h6>
        </div>
        <div class="card-body pt-4 p-3">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Nama Alat:</strong>
                    <p>{{ $tools->nama_alat ?? '-'?? '-'}}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Nomor Alat:</strong>
                    <p>{{ $tools->alat->nomor ?? '-' ?? '-'}}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>HSE Inspector:</strong>
                    <p>{{ $tools->hse_inspector ?? '-'}}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Tanggal Pemeriksaan:</strong>
                    <p>{{ $tools->tanggal_pemeriksaan?? '-' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Status Pemeriksaan:</strong>
                    <p>{{ $tools->status_pemeriksaan ?? '-'}}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Status:</strong>
                    <p>{{ $tools->status ?? '-'}}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Penulis:</strong>
                    <p>{{ $tools->writer ?? '-'}}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Dibuat Pada:</strong>
                    <p>{{ $tools->created_at ??'-'}}</p>
                </div>
                <div class="col-md-12 mb-3">
                    <strong>Foto Pemeriksaan:</strong><br>
                    @if ($tools->foto && Storage::disk('public')->exists($tools->foto))
                        <img src="{{ asset('storage/' . $tools->foto) ?? '-'}}" alt="Foto Pemeriksaan" class="img-fluid rounded" style="max-height: 400px;">
                    @else
                        <p>Tidak ada foto tersedia.</p>
                    @endif
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('adminsystem.tool.index') ?? '-'}}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>

@endsection
