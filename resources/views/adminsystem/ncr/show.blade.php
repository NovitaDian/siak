@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid">
    <h2 class="text-black font-weight-bolder text-center mb-4">
        NONCONFORMITY REPORT AND CORRECTIVE/PREVENTIVE ACTION REQUEST
    </h2>

    <div class="card mx-auto w-100" style="max-width: 150%;">
        <div class="card-header pb-0 px-3">
            <h6 class="mb-0">{{ __('DATA UMUM') }}</h6>
        </div>
        <div class="card-body pt-4 p-3">
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Shift Kerja</label>
                    <div class="border p-2 rounded bg-light">{{ $ncr->tanggal_shift_kerja }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Shift Kerja</label>
                    <div class="border p-2 rounded bg-light">{{ $ncr->shift_kerja }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama H&S Officer 1 / Lead Auditor</label>
                    <div class="border p-2 rounded bg-light">{{ $ncr->nama_hs_officer_1 }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama H&S Officer 2 / Auditor</label>
                    <div class="border p-2 rounded bg-light">{{ $ncr->nama_hs_officer_2 }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Audit / Pengawasan</label>
                    <div class="border p-2 rounded bg-light">{{ $ncr->tanggal_audit }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Auditee/Owner Area</label>
                    <div class="border p-2 rounded bg-light">{{ $ncr->nama_auditee }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Perusahaan</label>
                    <div class="border p-2 rounded bg-light">{{ $ncr->perusahaan }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Bagian/Department</label>
                    <div class="border p-2 rounded bg-light">{{ $ncr->nama_bagian }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Element / Referensi NCR</label>
                    <div class="border p-2 rounded bg-light">{{ $ncr->element_referensi_ncr }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kategori Ketidaksesuaian</label>
                    <div class="border p-2 rounded bg-light">{{ $ncr->kategori_ketidaksesuaian }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Deskripsi Ketidaksesuaian</label>
                    <div class="border p-2 rounded bg-light">{{ $ncr->deskripsi_ketidaksesuaian}}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tindak Lanjut</label>
                    <div class="border p-2 rounded bg-light">{{$ncr->tindak_lanjut}}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Estimasi Penyelesaian</label>
                    <div class="border p-2 rounded bg-light">{{ $ncr->estimasi }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Foto</label><br>
                    @if($ncr->foto)
                    <img src="{{ asset('storage/' . $ncr->foto) }}" alt="Foto NCR" class="img-fluid rounded" style="max-height: 300px;">
                    @else
                    <div class="border p-2 rounded bg-light">Tidak ada foto</div>
                    @endif
                </div>
               <form action="{{ route('adminsystem.ncr.index') }}" method="GET" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary btn-sm active mb-0 text-white" role="button" aria-pressed="true">
                            Kembali
                        </button>
                    </form>
            </div>
        </div>
    </div>
</div>
@endsection