@extends('layouts.user_type.tamu')

@section('content')

<div class="row">

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

                                <form action="{{ route('tamu.destroy', $note->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus catatan ini?')">Hapus</button>
                                </form>
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