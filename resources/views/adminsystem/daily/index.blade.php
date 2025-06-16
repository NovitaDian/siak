@extends('layouts.user_type.auth')

@section('content')
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin: 10px;">
    {{ session('success') }}
</div>
@endif
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>REQUEST</h6>
                </div>
                <div class="card-body px-4 pt-4 pb-4">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="dataTableReq">
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
                                    <td class="text-center text-xs">{{ $request->nama_pengirim }}</td>
                                    <td class="text-center text-xs">{{ $request->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center text-xs">{{ $request->type }}</td>
                                    <td class="text-center text-xs">{{ $request->reason }}</td>
                                    <td class="text-center" id="status-{{$request->id}}">{{ $request->status }}</td>
                                    <td class="text-center">
                                        @if ($request->status == 'Pending')
                                        <button
                                            class="btn btn-success btn-xs me-1"
                                            onclick="approveRequest('{{ $request->id }}')"
                                            title="Approve this request">
                                            <i class="fas fa-check m-1"></i> Approve
                                        </button>

                                        <button
                                            class="btn btn-danger btn-xs me-1"
                                            onclick="rejectRequest('{{ $request->id }}')"
                                            title="Reject this request">
                                            <i class="fas fa-times m-1"></i> Reject
                                        </button>
                                        @endif

                                        <form
                                            action="{{ route('adminsystem.daily.show', ['id' => $request->id]) }}"
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

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Draft Daily Activities & Improvement Report</h6>
                    <form action="{{ route('adminsystem.daily.create') }}" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary active text-white" role="button" aria-pressed="true">
                            Tambah
                        </button>
                    </form>
                </div>
                <div class="card-body px-4 pt-4 pb-4">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="dataTableDraft">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Shift Kerja</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shift Kerja</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HSE Officer</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rincian</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dailys as $daily)
                                <tr>
                                    <td class="text-center text-xs">
                                        <div class="d-flex justify-content-center align-items-center px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ \Carbon\Carbon::parse($daily->tanggal_shift_kerja)->format('d/m/Y') }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center text-xs">
                                        <p class="text-xs font-weight-bold mb-0">{{ $daily->shift_kerja }}</p>
                                    </td>
                                    <td class="text-center text-xs">
                                        <p class="text-xs font-weight-bold mb-0">{{ $daily->nama_hse_inspector }}</p>
                                    </td>

                                    <td class="text-center text-xs">
                                        <p class="text-xs font-bold mb-0" style="max-width: 200px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                            {{ $daily->rincian_laporan }}
                                        </p>
                                    </td>

                                    <td class="align-middle text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-1 flex-nowrap" style="flex-wrap: nowrap;">

                                            <form action="{{ route('adminsystem.daily.destroy', $daily->id) }}" method="POST" class="m-0">
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
                                                        <a class="dropdown-item" href="{{ route('adminsystem.daily.edit', $daily->id) }}">
                                                            <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('adminsystem.daily.show', ['id' => $daily->id]) }}" method="GET" class="m-0">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-eye me-1" style="font-size: 12px;"></i> Show
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('adminsystem.daily.draft_destroy', $daily->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                                    </td>



                                    <script>
                                        function editAction() {
                                            window.location.href = "{{ route('adminsystem.daily.edit', $daily->id) }}";
                                        }
                                    </script>
                    </div>
                    </td>
                    </tr>
                    @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Sent Daily Activities & Improvement Report</h6>


                        <form action="{{ route('adminsystem.daily.index') }}" method="GET" class="row g-3 px-4 mb-3">
                            <div class="col-12 col-md-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="end_date" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>

                            <div class="col-12 col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-sm btn-warning w-50">Filter</button>
                            </div>

                            <div class="col-12 col-md-3 d-flex justify-content-md-end justify-content-start align-items-end">
                                <div class="dropdown w-100 w-md-auto">
                                    <button class="btn btn-sm btn-primary dropdown-toggle w-100 w-md-auto" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-download me-1"></i> Unduh
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('adminsystem.tool.export', request()->all()) }}">
                                                <i class="fas fa-file-excel text-success me-2"></i> Excel
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('adminsystem.tool.exportPdf', request()->all()) }}">
                                                <i class="fas fa-file-pdf text-danger me-2"></i> PDF
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body px-4 pt-4 pb-4">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="dataTableSent">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Shift Kerja</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shift Kerja</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HSE Inspector</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rincian</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($daily_fixs as $daily_fix)
                                    <tr>
                                        <td class="text-center text-xs">
                                            <div class="d-flex justify-content-center align-items-center px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-xs">{{ \Carbon\Carbon::parse($daily_fix->tanggal_shift_kerja)->format('d/m/Y') }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center text-xs">
                                            <p class="text-xs font-weight-bold mb-0">{{ $daily_fix->shift_kerja }}</p>
                                        </td>
                                        <td class="text-center text-xs">
                                            <p class="text-xs font-weight-bold mb-0">{{ $daily_fix->nama_hse_inspector }}</p>
                                        </td>
                                        <td class="text-center text-xs">
                                            <p class="text-xs font-bold mb-0" style="max-width: 200px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                                {{ $daily_fix->rincian_laporan }}
                                            </p>
                                        </td>


                                        <td class="align-middle text-center">
                                            @if ($daily_fix->status == 'Nothing')
                                            <button class="btn btn-secondary btn-xs" onclick="showRequestModal('{{ $daily_fix->id }}')">
                                                <i class="fas fa-paper-plane me-1" style="font-size: 12px;"></i> Request
                                            </button>

                                            @elseif ($daily_fix->status == 'Pending')
                                            <span class="badge bg-warning text-dark">Pending</span>


                                            @elseif ($daily_fix->status == 'Approved')
                                            @php
                                            // Cari request: prioritaskan 'Delete', jika tidak ada ambil 'Edit'
                                            $request = $requests->where('sent_daily_id', $daily_fix->id)->firstWhere('type', 'Delete')
                                            ?? $requests->where('sent_daily_id', $daily_fix->id)->firstWhere('type', 'Edit');
                                            @endphp

                                            @if ($request)
                                            <div class="dropdown d-inline">
                                                <button class="btn btn-success btn-xs dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-check-circle me-1" style="font-size: 12px;"></i> Approved
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if (strcasecmp($request->type, 'Edit') === 0)
                                                    <li>
                                                        <a href="{{ route('adminsystem.daily.sent_edit', $daily_fix->id) }}" class="dropdown-item">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                    </li>
                                                    @elseif (strcasecmp($request->type, 'Delete') === 0)
                                                    <li>
                                                        <form action="{{ route('adminsystem.daily.sent_destroy', $daily_fix->id) }}" method="POST" onsubmit="return confirm('Anda yakin akan menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash-alt me-1"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @else
                                                    <li><span class="dropdown-item text-danger">Unknown request type</span></li>
                                                    @endif
                                                </ul>
                                            </div>
                                            @else
                                            <span class="text-danger">No corresponding request found</span>
                                            @endif

                                            @elseif ($daily_fix->status == 'Rejected')
                                            <span class="badge bg-danger text-white">Rejected</span>
                                            @endif
                                        </td>

                                        <td class="align-middle text-center">
                                            <a href="{{ route('adminsystem.daily.sent_show', $daily_fix->id) }}"
                                                class="btn btn-info btn-xs"> <i class="fas fa-eye me-1" style="font-size: 12px;"></i> Show
                                            </a>
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

    <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalLabel">Request Edit/Delete</h5>
                </div>
                <div class="modal-body">
                    <form id="requestForm" method="POST" action="{{ route('adminsystem.daily.storeRequest') }}">
                        @csrf
                        <input type="hidden" id="sentdailyId" name="sent_daily_id">
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
    </div>
</main>
<!-- Include jQuery and Bootstrap JS -->

<script>
    // Ambil CSRF token dari meta tag
    var csrfToken = '{{ csrf_token() }}';

    // Fungsi untuk menampilkan modal permintaan edit/hapus
    function showRequestModal(id) {
        $('#sentdailyId').val(id);
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

    // Fungsi untuk menyetujui permintaan
    function approveRequest(id) {
        $.ajax({
            url: "{{ route('adminsystem.daily.approve', '') }}/" + id, // Menggunakan route Laravel
            type: 'POST',
            data: {
                _token: csrfToken // Menggunakan CSRF token
            },
            success: function(response) {
                if (response.success) {
                    $('#status-' + id).text('Approved'); // Perbarui status di tabel
                } else {
                    console.error("Approval failed:", response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error approving request:", error);
            }
        });
    }

    // Fungsi untuk menolak permintaan
    function rejectRequest(id) {
        $.ajax({
            url: "{{ route('adminsystem.daily.reject', '') }}/" + id, // Menggunakan route Laravel
            type: 'POST',
            data: {
                _token: csrfToken // Menggunakan CSRF token
            },
            success: function(response) {
                if (response.success) {
                    $('#status-' + id).text('Rejected'); // Perbarui status di tabel
                }
            },
            error: function(xhr, status, error) {
                console.error("Error rejecting request:", error);
            }
        });
    }



    // Fungsi untuk mengedit item yang telah dikirim
    function sentEditAction(id) {
        window.location.href = "{{ url('adminsystem/daily/sent_edit') }}/" + id; // Menggunakan URL Laravel
    }
</script>


<script>
    $(document).ready(function() {
        $('#dataTableReq').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            ordering: true,
            searching: true,
            info: true,
            paging: true
        });
    });

    $(document).ready(function() {
        $('#dataTableDraft').DataTable({
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 100],
            "ordering": true,
            "searching": true,
            "info": true,
            "paging": true,
            "responsive": true
        });
    });
    $(document).ready(function() {
        $('#dataTableSent').DataTable({
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 100],
            "ordering": true,
            "searching": true,
            "info": true,
            "paging": true,
            "responsive": true
        });
    });
</script>
@endsection