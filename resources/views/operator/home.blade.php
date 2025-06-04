@extends('layouts.user_type.operator')

@section('content')

<div class="row">
    <!-- First row with 3 columns -->
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card d-flex flex-row">
            <img src="../assets/img/work-injury.png" alt="Work Injury Logo" class="img-fluid" style="width: 100px; height: 100px; object-fit: cover; margin-top: 30px; margin-left: 30px;">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="font-weight-bolder text-center">INCIDENT & ACCIDENT</h5>
                <br>
                <button class="btn btn-primary w-100" onclick="location.href='{{ route('operator.incident.index') }}'">GO</button>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card d-flex flex-row">
            <img src="../assets/img/worker.png" alt="Safety Behavior Logo" class="img-fluid" style="width: 100px; height: 100px; object-fit: cover; margin-top: 30px; margin-left: 30px;">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="font-weight-bolder text-center">SAFETY BEHAVIOR & PPE COMPLIANCE</h5>
                <button class="btn btn-primary w-100" onclick="location.href='{{ route('operator.ppe.index') }}'">GO</button>
            </div>
        </div>
    </div>


    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
            <div class="card d-flex flex-row">
                <img src="../assets/img/lab-test.png" alt="NCR Logo" class="img-fluid" style="width: 100px; height: 100px; object-fit: cover; margin-top: 30px; margin-left: 30px;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="font-weight-bolder text-center">NON CONFORMITY REPORT</h5>
                    <button class="btn btn-primary w-100" onclick="location.href='{{ route('operator.ncr.index') }}'">GO</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-xl-4 col-sm-6 mb-4">
            <div class="card d-flex flex-row">
                <img src="../assets/img/settings.png" alt="Tool Inspection Logo" class="img-fluid" style="width: 100px; height: 100px; object-fit: cover; margin-top: 30px; margin-left: 30px;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="font-weight-bolder text-center">TOOL & EQUIPMENT INSPECTION</h5>
                    <button class="btn btn-primary w-100" onclick="window.location.href='{{ route('operator.tool.index') }}';">Go</button>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-4">
            <div class="card d-flex flex-row">
                <img src="../assets/img/daily.png" alt="Daily Activities Logo" class="img-fluid" style="width: 100px; height: 100px; object-fit: cover; margin-top: 30px; margin-left: 30px;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="font-weight-bolder text-center">DAILY ACTIVITIES & IMPROVEMENT</h5>
                    <button class="btn btn-primary w-100" onclick="location.href='{{ route('operator.daily.index') }}'">Go</button>
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
                                <p class="text-end">Dibuat pada: {{ $note->created_at->format('d/m/Y') }}</p>
                                <p class="text-end">Ditulis oleh: {{ $note->writer }}</p>
                                <br>
                                <p>Catatan: {{ $note->note }}</p>

                                @if ($note->attachments->isNotEmpty())
                                <p>Attachment:</>
                                    @foreach ($note->attachments as $attachment)
                                    <a href="{{ asset('storage/' . optional($attachment)->file_path) }}" target="_blank">{{ optional($attachment)->file_name }}</a><br>
                                    @endforeach
                                    @endif

                                <form action="{{ route('operator.destroy', $note->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus catatan ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                        <button class="btn btn-success btn-sm" onclick="addNewTimelineItem()">Tambah Kolom Baru</button>
                    </div>

                    <form action="{{ route('operator.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="timeline-item" id="new-timeline-item" style="display: none;">
                            <div class="timeline-content">
                                <div class="form-group">
                                    <label for="note">Catatan:</label>
                                    <textarea class="form-control" name="note" id="note" rows="3" placeholder="Tulis catatan Anda di sini..."></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="attachment">Attachment:</label>
                                    <input type="file" class="form-control" name="attachment" id="attachment">
                                </div>
                                <div class="mt-2 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>

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
            padding: 10px 0;
        }

        .timeline-content {
            padding: 10px;
            width: 100%;
            margin-bottom: 5px;
            margin-left: 5px;
            margin-right: 10px;


        }

        .timeline-content .btn {
            margin-top: 5px;
        }

        #timeline-container {
            max-height: 400px;
            overflow-y: auto;
        }

        .form-group label {
            font-size: 0.85rem;
        }

        .form-group .form-control {
            font-size: 0.9rem;
            padding: 4px 8px;
        }
    </style>


    @endsection