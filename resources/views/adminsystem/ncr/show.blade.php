@extends('layouts.user_type.auth')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
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
                    <div class="border p-2 rounded bg-light">{{ $ncr->nama_hs_officer_2 ?? '-'}}</div>
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
                    <div class="border p-2 rounded bg-light">{{ $ncr->pers->perusahaan_name }}</div>
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
                    <label class="form-label fw-bold">Durasi</label>
                    <div class="border p-2 rounded bg-light">{{ $ncr->durasi_ncr ?? '-'}}</div>
                </div>
                <div>
                    <label class="form-label fw-bold">Status</label>
                    <div class="border p-2 rounded bg-light">{{ $ncr->status_note ?? '-'}}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Foto</label><br>
                    @if($ncr->foto)
                    <img src="{{ asset('storage/' . $ncr->foto) }}" alt="Foto NCR" class="img-fluid rounded" style="max-height: 300px;">
                    @else
                    <div class="border p-2 rounded bg-light">Tidak ada foto</div>
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Foto Close</label><br>
                    @if($ncr->foto_closed)
                    <img src="{{ asset('storage/' . $ncr->foto_closed) }}" alt="Foto NCR" class="img-fluid rounded" style="max-height: 300px;">
                    @else
                    <div class="border p-2 rounded bg-light">Tidak ada foto</div>
                    @endif
                </div>


            </div>
            <div class="mt-4 text-end">
                <a href="{{ route('adminsystem.ncr.exportSinglePdf', $ncr->id) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-2"></i> Download PDF
                </a>
            </div>

        </div>
    </div>
</div>
@endsection