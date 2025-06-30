@extends('layouts.user_type.operator')

@section('content')

<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
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
                    <img src="{{ asset('storage/' . $tools->foto) }}"
                        alt="{{ $tools->description }}"
                        style="width:100%; height:100%; object-fit:cover;">
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('operator.tool.index') ?? '-'}}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>

@endsection