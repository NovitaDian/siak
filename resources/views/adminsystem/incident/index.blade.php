@extends('layouts.user_type.auth')

@section('content')
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin: 10px;">
    {{ session('success') }}
</div>
@endif
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 200%; ">

            <div class="row">
                <div class="col-12">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Request</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <div class="card-header pb-0">
                                <table class="table align-items-center mb-0" id="requestTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Pengirim</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu Pengajuan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Pengajuan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alasan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="notificationTableBody">
                                        @foreach ($requests as $request)
                                        <tr>
                                            <td class="text-center text-xs font-weight-bold">{{ $request->nama_pengirim }}</td>
                                            <td class="text-center text-xs font-weight-bold">{{ $request->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center text-xs font-weight-bold">{{ $request->type }}</td>
                                            <td class="text-center text-xs font-weight-bold">{{ $request->reason }}</td>
                                            <td class="text-center text-xs font-weight-bold" id="status-{{ $request->id }}">
                                                {{ $request->status }}
                                            </td>
                                            <td class="text-center">
                                                @if ($request->status == 'Pending')
                                                <form action="{{ route('adminsystem.incident.approve', $request->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-xs me-1"
                                                        onclick="return confirm('Yakin ingin menyetujui permintaan ini?')">
                                                        <i class="fas fa-check m-1"></i> Approve
                                                    </button>
                                                </form>

                                                <form action="{{ route('adminsystem.incident.reject', $request->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-xs me-1"
                                                        onclick="return confirm('Yakin ingin menolak permintaan ini?')">
                                                        <i class="fas fa-times m-1"></i> Reject
                                                    </button>
                                                </form>
                                                @endif
                                                <form
                                                    action="{{ route('adminsystem.incident.sent_show', ['id' => $request->id]) }}"
                                                    method="GET"
                                                    style="display:inline;"
                                                    title="View details">
                                                    <button type="submit" class="btn btn-light btn-xs">
                                                        <i class="fas fa-eye"></i> Show
                                                    </button>
                                                </form>
                                            </td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Font Awesome CDN (if not already included elsewhere) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">




    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Draft</h6>
                    <form action="{{ route('adminsystem.incident.create') }}" method="GET" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm text-white">
                            Tambah
                        </button>
                    </form>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <div class="card-header pb-0">
                            <table class="table align-items-center mb-0" id="draftTable">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Shift Kerja</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shift Kerja</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HSE InspectorI</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ada Kejadian</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Klasifikasi</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Korban</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($incidents as $incident)
                                    <tr>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ \Carbon\Carbon::parse($incident->shift_date)->format('d/m/Y') }}</h6>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $incident->shift }}</p>
                                        </td>

                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $incident->safety_officer_1 }}</p>
                                        </td>

                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0">{{ $incident->status_kejadian }}</p>

                                        </td>

                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $incident->klasifikasi_kejadiannya ?? "-" }}</span>
                                        </td>

                                        <td class="align-middle text-center text-sm">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ $incident->ada_korban == 'Tidak' ? 'Ada' : 'Tidak' }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex justify-content-center align-items-center gap-1 flex-nowrap" style="flex-wrap: nowrap;">

                                                <form action="{{ route('adminsystem.incident.destroy', $incident->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-send btn-xs" onclick="return confirm('Anda yakin akan mengirim dokumen?')" title="Kirim">
                                                        <i class="fas fa-paper-plane me-1" style="font-size: 12px;"></i> Send
                                                    </button>
                                                </form>

                                                <div class="dropdown">
                                                    <button class="btn btn-secondary btn-xs dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-cog me-1" style="font-size: 12px;"></i> Options
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('adminsystem.incident.edit', $incident->id) }}">
                                                                <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('adminsystem.incident.show', ['id' => $incident->id]) }}" method="GET" class="m-0">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas fa-eye me-1" style="font-size: 12px;"></i> Show
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('adminsystem.incident.draft_destroy', $incident->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-trash-alt me-1" style="font-size: 12px;"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <!-- Font Awesome & Bootstrap JS (pindahkan ke layout utama jika belum ada) -->
                                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                                        </td>
                                        <script>
                                            function editAction() {
                                                // Redirect to the edit form for the item
                                                window.location.href = "{{ route('adminsystem.incident.edit', $incident->id) }}";
                                            }
                                        </script>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Sent Document</h6>
                    <br>
                    <form action="{{ route('adminsystem.incident.index') }}" method="GET" class="row g-3 px-4 mb-3">
                        <div class="col-12 col-md-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="end_date" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>

                        <div class="col-12 col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-sm btn-warning w-50" style="margin-top: 20px; margin-bottom: 6px">Filter</button>
                        </div>

                        <div class="col-12 col-md-3 d-flex justify-content-md-end justify-content-start align-items-end">
                            <div class="dropdown w-100 w-md-auto">
                                <button class="btn btn-sm btn-primary dropdown-toggle w-100 w-md-auto" style="margin-bottom: 5px" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-download me-1"></i> Unduh
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('adminsystem.incident.export', request()->all()) }}">
                                            <i class="fas fa-file-excel text-success me-2"></i> Excel
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('adminsystem.incident.exportPdf', request()->all()) }}">
                                            <i class="fas fa-file-pdf text-danger me-2"></i> PDF
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>


                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <div class="card-header pb-0">
                            <table class="table align-items-center mb-0" id="sentTable">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Shift Kerja</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shift Kerja</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HSE InspectorI</th>
                                        <th style="width: 80px;" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ada Kejadian</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Klasifikasi</th>
                                        <th style="width: 80px;" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Korban</th>
                                        <th style="width: 200px;" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request</th>
                                        <th style="width: 200px;" class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($incident_fixs as $incident_fix)
                                    <tr>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ \Carbon\Carbon::parse($incident_fix->shift_date)->format('d/m/Y') }}</h6>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $incident_fix->shift }}</p>
                                        </td>

                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $incident_fix->safety_officer_1 }}</p>
                                        </td>

                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0">{{ $incident_fix->status_kejadian }}</p>

                                        </td>

                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $incident_fix->klasifikasi_kejadiannya ?? "-" }}</span>
                                        </td>

                                        <td class="align-middle text-center text-sm">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ $incident_fix->ada_korban == 'Tidak' ? 'Ada' : 'Tidak' }}
                                            </span>
                                        </td>

                                        <td class="align-middle text-center">
                                            @if ($incident_fix->status_request == 'Nothing')
                                            <button class="btn btn-info btn-xs" onclick="showRequestModal('{{ $incident_fix->id }}')">
                                                <i class="fas fa-paper-plane me-1" style="font-size: 12px;"></i> Request
                                            </button>

                                            @elseif ($incident_fix->status_request == 'Pending')
                                            <span class="badge bg-warning text-dark">Pending</span>

                                            @elseif ($incident_fix->status_request == 'Approved')
                                            @php
                                            $request = $requests->firstWhere('sent_incident_id', $incident_fix->id);
                                            \Log::info('Incident Fix ID: ' . $incident_fix->id . ' Looking for Request with sent_incident_id: ' . $incident_fix->id);
                                            \Log::info('Requests: ', $requests->toArray());
                                            @endphp

                                            @if ($request)
                                            <div class="dropdown d-inline">
                                                <button class="btn btn-success btn-xs dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-check-circle me-1" style="font-size: 12px;"></i> Approved
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if ($request->type == 'Edit')
                                                    <li>
                                                        <a href="{{ route('adminsystem.incident.sent_edit', $incident_fix->id) }}" class="dropdown-item">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                    </li>
                                                    @elseif ($request->type == 'Delete')
                                                    <li>
                                                        <form action="{{ route('adminsystem.incident.sent_destroy', $incident_fix->id) }}" method="POST" onsubmit="return confirm('Anda yakin akan menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash-alt me-1"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            @else
                                            <span class="text-danger" title="Request data not found"><i class="fas fa-exclamation-triangle me-1"></i> No request found</span>
                                            @endif

                                            @elseif ($incident_fix->status_request == 'Rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                            @endif

                                            <!-- Font Awesome -->
                                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                                        </td>

                                        <td class="align-middle text-center">
                                            <form
                                                action="{{ route('adminsystem.incident.sent_show', ['id' => $incident_fix->id]) }}"
                                                method="GET"
                                                style="display:inline;"
                                                title="View details">
                                                <button type="submit" class="btn btn-light btn-xs">
                                                    <i class="fas fa-eye"></i> Show
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="requestModalLabel">Request Edit/Delete</h5>
                    </div>
                    <div class="modal-body">
                        <form id="requestForm" method="POST" action="{{ route('adminsystem.incident.storeRequest') }}">
                            @csrf
                            <input type="hidden" id="sentincidentId" name="sent_incident_id">
                            <div class="form-group">
                                <label for="requestType"> Jenis Request </label><br>
                                <input type="radio" id="Edit" name="type" value="Edit" required>
                                <label for="Edit">Edit</label>
                                <input type="radio" id="Delete" name="type" value="Delete" required>
                                <label for="Delete">Delete</label>
                            </div>
                            <div class="form-group">
                                <label for="reason">Alasan Pengajuan Request</label>
                                <textarea class="form-control" id="reason" name="reason" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</main>
<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize data tables
        $('#requestTable').DataTable({
            pageLength: 5,
            searching: true,
            ordering: true,
            responsive: true
        });

        $('#draftTable').DataTable({
            pageLength: 5,
            searching: true,
            ordering: true,
            responsive: true
        });

        $('#sentTable').DataTable({
            pageLength: 5,
            searching: true,
            ordering: true,
            responsive: true
        });
    });

    // Ambil CSRF token dari meta tag
    var csrfToken = '{{ csrf_token() }}';

    // Fungsi untuk menampilkan modal permintaan edit/hapus
    function showRequestModal(id) {
        $('#sentincidentId').val(id);
        $('#requestModal').modal('show');
    }

    // Menangani pengiriman form permintaan
    $('#requestForm').on('submit', function(e) {
        e.preventDefault(); // Mencegah pengiriman form default

        $.ajax({
            url: $(this).attr('action'), // URL dari form
            type: 'POST',
            data: $(this).serialize() + '&_token=' + csrfToken, // Mengambil data dari form dan menambahkan CSRF token
            success: function(response) {
                if (response.success) {
                    alert(response.message); // Tampilkan pesan sukses
                    $('#requestModal').modal('hide'); // Tutup modal
                    location.reload(); // Reload halaman untuk memperbarui data
                } else {
                    alert(response.message); // Tampilkan pesan kesalahan
                }
            },
            error: function(xhr) {
                console.error("Error:", xhr.responseText); // Tampilkan kesalahan di konsol
                alert('An error occurred. Please try again.'); // Tampilkan pesan kesalahan umum
            }
        });
    });


    // Fungsi untuk mengedit item
    function editAction(id) {
        window.location.href = "{{ url('adminsystem/incident/edit') }}/" + id; // Menggunakan URL Laravel
    }

    // Fungsi untuk mengedit item yang telah dikirim
    function sentEditAction(id) {
        window.location.href = "{{ url('adminsystem/incident/sent_edit') }}/" + id; // Menggunakan URL Laravel
    }
</script>
@endsection