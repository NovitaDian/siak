@extends('layouts.user_type.operator')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header pb-0">
                    <h4 class="text-center font-weight-bolder">DETAIL DAILY ACTIVITIES & IMPROVEMENT REPORT</h4>
                </div>
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tanggal Shift Kerja:</strong>
                            <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($daily->tanggal_shift_kerja)->format('d-m-Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Shift Kerja:</strong>
                            <p class="text-muted mb-0">{{ $daily->shift_kerja }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>HSE Inspector:</strong>
                            <p class="text-muted mb-0">{{ $daily->inspectors->name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Rincian Laporan:</strong>
                        <div class="border p-3 rounded bg-light" style="max-width: 100%; white-space: pre-line;">
                            {{ $daily->rincian_laporan }}
                        </div>
                    </div>

                  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection