@extends('layouts.user_type.operator')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="container-fluid py-4">

    </div>

    <br>
    <div class="row" style="display: none;">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>REQUEST</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <div class="card-header pb-0">
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
                                            <form action="{{ route('operator.tool.approve', $request->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-xs">Approve</button>
                                            </form>

                                            <form action="{{ route('operator.tool.reject', $request->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-xs">Reject</button>
                                            </form>
                                            @endif
                                            <form action="{{ route('operator.tool.show', ['id' => $request->sent_tool_id]) }}" method="GET" style="display:inline;">
                                                <button type="submit" class="btn btn-info btn-xs">
                                                    Show
                                                </button>
                                            </form>
                                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Draft tool</h6>
                    <form action="{{ route('operator.tool.create') }}" method="GET" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">
                            Tambah
                        </button>
                    </form>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <div class="card-header pb-0">
                            <table class="table align-items-center mb-0" id="dataTableDraft">
                                <thead>
                                    <tr>

                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Alat</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hse Inspector</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Pemeriksaan</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Pemeriksaan</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tools as $tool)
                                    <tr>
                                        <td class="text-center text-xs">{{ $tool->nama_alat }}-{{ $tool->alat->nomor ?? '-' }}</td>
                                        <td class="text-center text-xs">{{ $tool->hse_inspector }}</td>
                                        <td class="text-center text-xs">{{ \Carbon\Carbon::parse($tool->tanggal_pemeriksaan)->format('d/m/Y') }}</td>
                                        <td class="text-center text-xs">{{ $tool->status_pemeriksaan }}</td>

                                        <td class="align-middle text-center">
                                            <div style="display: flex; justify-content: center; align-items: center;">
                                                <!-- Tombol Edit -->
                                                <a href="{{ route('operator.tool.edit', $tool->id) }}"
                                                    class="btn btn-warning">
                                                    <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit
                                                </a>


                                                <!-- Tombol Delete -->
                                                <form action="{{ route('operator.tool.destroy', $tool->id) }}" method="POST" style="margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-info"
                                                        onclick="return confirm('Anda yakin akan mengirim dokumen?')"
                                                        title="Kirim"> <i class="fas fa-paper-plane me-1" style="margin-right: 4px; font-size: 12px;"></i> Send
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Font Awesome (pindahkan ke layout utama jika sudah dimuat global) -->
                                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                    <h6>Sent Tool</h6>
                    <form action="{{ route('operator.tool.index') }}" method="GET" class="row g-3 px-4 mb-3">
                        <div class="col-12 col-md-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="end_date" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>

                        <div class="col-12 col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-sm btn-primary w-50">Filter</button>
                        </div>

                        <div class="col-12 col-md-3 d-flex justify-content-md-end justify-content-start align-items-end gap-2">
                            <a href="{{ route('operator.tool.export', request()->all()) }}" class="btn btn-sm btn-success w-100 w-md-auto">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </a>
                            <a href="{{ route('operator.tool.exportPdf', request()->all()) }}" class="btn btn-sm btn-danger w-100 w-md-auto">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </a>
                        </div>
                    </form>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table class="table align-items-center mb-0" id="dataTableSent">
                                <thead>
                                    <tr>

                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Alat</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hse Inspector</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Pemeriksaan</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tool_fixs as $tool_fix)
                                    <tr>
                                        <td class="text-center text-xs">{{ $tool_fix->nama_alat }}-{{ $tool_fix->alat->nomor ?? '-' }}</td>
                                        <td class="text-center text-xs">{{ $tool_fix->hse_inspector }}</td>
                                        <td class="text-center text-xs">{{ \Carbon\Carbon::parse($tool_fix->tanggal_pemeriksaan)->format('d/m/Y') }}</td>
                                        <td class="text-center text-xs">{{ $tool_fix->status_pemeriksaan }}</td>
                                         <td class="align-middle text-center">
                                                @if ($tool_fix->status == 'Nothing')
                                                <a href="{{ route('operator.tool.show', $tool_fix->id) }}"
                                                    class="btn btn-info btn-xs"> <i class="fas fa-eye me-1" style="font-size: 12px;"></i> Show
                                                </a>

                                                <button class="btn btn-secondary btn-xs"
                                                    onclick="showRequestModal('{{ $tool_fix->id }}')">Request</button>

                                                @elseif ($tool_fix->status == 'Pending')
                                                <span class="text-warning">Pending</span>

                                                @elseif ($tool_fix->status == 'Approved')
                                                @php
                                                // Cari request: prioritaskan 'Delete', jika tidak ada ambil 'Edit'
                                                $request = $requests->where('sent_tool_id', $tool_fix->id)->firstWhere('type', 'Delete')
                                                ?? $requests->where('sent_tool_id', $tool_fix->id)->firstWhere('type', 'Edit');
                                                @endphp

                                                @if ($request)
                                                @if (strcasecmp($request->type, 'Edit') === 0)
                                                <a href="{{ route('operator.tool.sent_edit', $tool_fix->id) }}"
                                                    class="btn btn-warning btn-xs"> <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit
                                                </a>
                                                @elseif (strcasecmp($request->type, 'Delete') === 0)
                                                <form action="{{ route('operator.tool.sent_destroy', $tool_fix->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-xs"
                                                        onclick="return confirm('Anda yakin akan menghapus data ini?')">
                                                        <i class="fas fa-trash-alt me-1" style="font-size: 12px;"></i> Delete
                                                    </button>
                                                </form>
                                                @else
                                                <span class="text-danger">Unknown request type</span>
                                                @endif
                                                @else
                                                <span class="text-danger">No corresponding request found</span>
                                                @endif

                                                @elseif ($tool_fix->status == 'Rejected')
                                                <span class="text-danger">Request Rejected</span>
                                                @endif
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

    <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalLabel">Request Edit/Delete</h5>
                </div>
                <div class="modal-body">
                    <form id="requestForm" method="POST" action="{{ route('operator.tool.storeRequest') }}">
                        @csrf
                        <input type="hidden" id="senttoolId" name="sent_tool_id">
                        <div class="form-group">
                            <label for="requestType">Request Type</label><br>
                            <input type="radio" id="Edit" name="type" value="Edit" required>
                            <label for="Edit">Edit</label>
                            <input type="radio" id="Delete" name="type" value="Delete" required>
                            <label for="Delete">Delete</label>
                        </div>
                        <div class="form-group">
                            <label for="reason">Reason for Request</label>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<script>
    // Ambil CSRF token dari meta tag
    var csrfToken = '{{ csrf_token() }}';

    // Fungsi untuk menampilkan modal permintaan edit/hapus
    function showRequestModal(id) {
        $('#senttoolId').val(id);
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

    // Ambil CSRF token dari meta tag
    var csrfToken = '{{ csrf_token() }}';

    function approveRequest(id) {
        $.ajax({
            url: "{{ route('operator.tool.approve', '') }}/" + id, // Menggunakan route Laravel
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
            url: `/operator/tool/reject/${id}`, // URL ke rute reject
            type: 'POST',
            data: {
                _token: csrfToken // Sertakan CSRF token untuk keamanan
            },
            success: function(response) {
                console.log("Success:", response);
                if (response.success) {
                    $('#status-' + id).text('Rejected'); // Perbarui status di tabel
                } else {
                    alert('Error: ' + response.message); // Tampilkan pesan kesalahan
                }
            },
            error: function(xhr, status, error) {
                console.error("Error rejecting request:", error);
                alert('An error occurred while rejecting the request.'); // Tampilkan pesan error
            }
        });
    }




    // Fungsi untuk mengedit item yang telah dikirim
    function sentEditAction(id) {
        window.location.href = "{{ url('operator/tool/sent_edit') }}/" + id; // Menggunakan URL Laravel
    }
</script>


<script>
    $(document).ready(function() {
        $('#dataTableReq').DataTable({
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