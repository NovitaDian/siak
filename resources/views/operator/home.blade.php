@extends('layouts.user_type.operator')

@section('content')

<div class="row">
    <!-- First row with 3 columns -->
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">INCIDENT & ACCIDENT</h5>
                    <br>
                    <div class="mb-3 text-center">
                        <img src="../assets/img/work-injury.png" alt="Work Injury Logo" class="img-fluid" style="max-width: 100px;">
                    </div>

                    <!-- DATA Button (Green) -->
                    <button class="btn btn-primary text-sm font-weight-bold mb-0 icon-move-right mt-auto" onclick="location.href='{{ route('operator.incident.index') }}'">
                        DATA
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">SAFETY BEHAVIOR & PPE COMPLIANCE</h5>
                    <div class="mb-3 text-center">
                        <img src="../assets/img/worker.png" alt="Safety Behavior Logo" class="img-fluid" style="max-width: 100px;">
                    </div>
                    <!-- DATA Button (Green) -->
                    <button class="btn btn-primary text-sm font-weight-bold mb-0 icon-move-right mt-auto" onclick="location.href='{{ route('operator.ppe.index') }}'">
                        DATA
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">NCR</h5>
                    <br>
                    <div class="mb-3 text-center">
                        <img src="../assets/img/lab-test.png" alt="NCR Logo" class="img-fluid" style="max-width: 100px;">
                    </div>
                    <!-- DATA Button (Green) -->
                    <button class="btn btn-primary text-sm font-weight-bold mb-0 icon-move-right mt-auto" onclick="location.href='{{ route('operator.ncr.index') }}'">
                        DATA
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 justify-content-center">
    <!-- Second row with 2 columns -->
    <div class="col-xl-4 col-sm-6 mb-xl-0">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">TOOL & EQUIPMENT INSPECTION</h5>
                    <div class="mb-3 text-center">
                        <img src="../assets/img/settings.png" alt="Tool Inspection Logo" class="img-fluid" style="max-width: 100px;">
                    </div>
                    <button class="btn btn-primary text-sm font-weight-bold mb-0 icon-move-right mt-auto" onclick="window.location.href='page.html';">
                        Go
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-sm-6 mb-xl-0">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2 text-center">DAILY ACTIVITIES & IMPROVEMENT</h5>
                    <div class="mb-3 text-center">
                        <img src="../assets/img/daily.png" alt="Daily Activities Logo" class="img-fluid" style="max-width: 100px;">
                    </div>
                    <button class="btn btn-primary text-sm font-weight-bold mb-0 icon-move-right mt-auto" onclick="location.href='{{ route('operator.daily.index') }}'">
                        Go
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
        <div class="card h-100 p-3">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Catatan & Unggahan</h6>
            </div>
            <div class="card-body pt-4 p-3">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <div id="timeline-container">
                    @foreach ($notes as $note)
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <p>Ditulis oleh: {{ $note->writer }}</p>
                            <p>Catatan: {{ $note->note }}</p>

                            @if ($note->attachments->isNotEmpty())
                            <p>Attachment:</p>
                            @foreach ($note->attachments as $attachment)
                            <a href="{{ asset('storage/' . optional($attachment)->file_path) }}" target="_blank">{{ optional($attachment)->file_name }}</a><br>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addNewTimelineItem() {
        document.getElementById('new-timeline-item').style.display = 'block';
    }
</script>

<style>
    .timeline-item {
        border-bottom: 1px solid #ddd;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .timeline-content {
        padding: 10px;
    }

    /* Tambahkan style berikut untuk responsif */
    #timeline-container {
        display: flex;
        flex-direction: column;
        /* Timeline vertikal */
    }

    .timeline-item {
        width: 100%;
        /* Lebar item timeline 100% */
    }

    /* Style tambahan untuk layar kecil */
    @media (max-width: 768px) {
        .timeline-item {
            flex-direction: column;
            /* Item timeline vertikal di layar kecil */
        }
    }
</style>

@endsection