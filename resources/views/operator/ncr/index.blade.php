@extends('layouts.user_type.operator')

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
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Draft NCR</h6>
                    <form action="{{ route('operator.ncr.create') }}" method="GET" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm active mb-0 text-white" role="button" aria-pressed="true">
                            Tambah
                        </button>
                    </form>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <div class="card-header pb-0">
                            <table class="table align-items-center mb-0" id="draftNcrTable">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Shift Kerja</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shift Kerja</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HSE Officer</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Audit</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Auditee</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ncrs as $ncr)
                                    <tr>
                                        <td class="text-center text-xs">
                                            <div class="d-flex justify-content-center align-items-center px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-xs"">{{ \Carbon\Carbon::parse($ncr->tanggal_shift_kerja)->format('d/m/Y') }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class=" text-center text-xs">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $ncr->shift_kerja }}</p>
                                        </td>

                                        <td class="text-center text-xs">
                                            <p class="text-xs font-weight-bold mb-0">{{ $ncr->nama_hs_officer_1 }}</p>
                                        </td>
                                        <td class="text-center text-xs">
                                            <p class="text-xs font-weight-bold mb-0">{{ \Carbon\Carbon::parse($ncr->tanggal_audit)->format('d/m/Y') }}</p>
                                        </td>
                                        <td class="text-center text-xs">
                                            <p class="text-xs font-weight-bold mb-0">{{ $ncr->nama_auditee }}</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex justify-content-center align-items-center gap-1 flex-nowrap" style="flex-wrap: nowrap;">

                                                <form action="{{ route('operator.ncr.destroy', $ncr->id) }}" method="POST" class="m-0">
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
                                                            <a class="dropdown-item" href="{{ route('operator.ncr.edit', $ncr->id) }}">
                                                                <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('operator.ncr.show', ['id' => $ncr->id]) }}" method="GET" class="m-0">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas fa-eye me-1" style="font-size: 12px;"></i> Show
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('operator.ncr.draft_destroy', $ncr->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                    <h6>Sent NCR</h6>
                    <form action="{{ route('operator.ncr.index') }}" method="GET" class="row g-3 px-4 mb-3">
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
                                        <a class="dropdown-item" href="{{ route('operator.ncr.export', request()->all()) }}">
                                            <i class="fas fa-file-excel text-success me-2"></i> Excel
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('operator.ncr.exportPdf', request()->all()) }}">
                                            <i class="fas fa-file-pdf text-danger me-2"></i> PDF
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Font Awesome (if not already included) -->
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome">

                    </form>
                </div>
                <div class=" card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center mb-0" id="sentNcrTable">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Shift Kerja</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shift Kerja</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HSE Inspector</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Audit</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Auditee</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Target Perbaikan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Durasi NCR</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Request</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Close</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ncr_fixs as $ncr_fix)
                                <tr class="sent-ncr-row" data-ncr-id="{{ $ncr_fix->id }}">
                                    <td class="text-center text-xs">{{ \Carbon\Carbon::parse($ncr_fix->tanggal_shift_kerja)->format('d/m/Y') }}</td>
                                    <td class="text-center text-xs">{{ $ncr_fix->shift_kerja }}</td>
                                    <td class="text-center text-xs">{{ $ncr_fix->nama_hs_officer_1 }}</td>
                                    <td class="text-center text-xs">{{ \Carbon\Carbon::parse($ncr_fix->tanggal_audit)->format('d/m/Y') }}</td>
                                    <td class="text-center text-xs">{{ $ncr_fix->nama_auditee }}</td>

                                    @php
                                    $estimasiDate = \Carbon\Carbon::parse($ncr_fix->estimasi);
                                    $isLate = now()->greaterThanOrEqualTo($estimasiDate) && $ncr_fix->status_ncr == 'Open';
                                    @endphp
                                    <td class="text-center text-xs">
                                        <span class="badge bg-{{ $isLate ? 'danger' : 'success' }}">
                                            {{ $estimasiDate->format('d/m/Y') }}
                                        </span>
                                    </td>

                                    <td class="text-center text-xs">{{ $ncr_fix->durasi_ncr }}</td>
                                    <td class="text-center text-xs">{{ $ncr_fix->status }}</td>

                                    {{-- Tombol Close/Open --}}
                                    <td class="align-middle text-center text-xs">
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            @if ($ncr_fix->status_ncr === 'Open')
                                            <a href="{{ route('operator.ncr.close', $ncr_fix->id) }}" class="btn btn-secondary btn-xs">
                                                <i class="fas fa-lock me-1" style="font-size: 12px;"></i> Close
                                            </a>
                                            @elseif ($ncr_fix->status_ncr === 'Closed')
                                            <span class="text-center text-xs">Closed</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Tombol Request/Edit/Delete --}}
                                    <td class="align-middle text-center text-xs">
                                        <div class="d-flex justify-content-center gap-1 flex-nowrap">
                                            @php
                                            $matchedRequest = $latestRequests->firstWhere('sent_ncr_id', $ncr_fix->id);
                                            $isDeleteRequest = $matchedRequest && $matchedRequest->type === 'Delete';
                                            $isEditRequest = $matchedRequest && $matchedRequest->type === 'Edit';
                                            @endphp

                                            @if ($ncr_fix->status === 'Nothing')
                                            <button class="btn btn-info btn-xs" onclick="showRequestModal('{{ $ncr_fix->id }}')">
                                                <i class="fas fa-paper-plane me-1" style="font-size: 12px;"></i> Request
                                            </button>

                                            @elseif ($ncr_fix->status === 'Approved')
                                            @if ($matchedRequest)
                                            <div class="dropdown d-inline">
                                                <button class="btn btn-success btn-xs dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-check-circle me-1" style="font-size: 12px;"></i> Approved
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if ($isEditRequest)
                                                    <li>
                                                        <a href="{{ $ncr_fix->status_ncr === 'Closed' 
                                                ? route('operator.ncr.edit_closed', $ncr_fix->id)
                                                : route('operator.ncr.sent_edit', $ncr_fix->id) }}"
                                                            class="dropdown-item">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                    </li>
                                                    @elseif ($isDeleteRequest)
                                                    <li>
                                                        <form action="{{ route('operator.ncr.sent_destroy', $ncr_fix->id) }}" method="POST" onsubmit="return confirm('Anda yakin akan menghapus data ini?')">
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
                                            <span class="text-danger" title="Request data not found">
                                                <i class="fas fa-exclamation-triangle me-1"></i> No request found
                                            </span>
                                            @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-xs">
                                        <a href="{{ $ncr_fix->status_ncr === 'Closed' 
                ? route('operator.ncr.closed_show', $ncr_fix->id) 
                : route('operator.ncr.sent_show', $ncr_fix->id) }}"
                                            class="dropdown-item"
                                            title="View details">
                                            <i class="fas fa-edit me-1"></i> Show
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

    <!-- Modal Request -->
    <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="requestForm" method="POST" action="{{ route('operator.ncr.storeRequest') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="requestModalLabel">Request Edit/Delete</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="sentNcrId" name="sent_ncr_id">
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
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



</main>

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

        $('#draftNcrTable').DataTable({
            pageLength: 5,
            searching: true,
            ordering: true,
            responsive: true
        });

        $('#sentNcrTable').DataTable({
            pageLength: 5,
            searching: true,
            ordering: true,
            responsive: true
        });
    });

    // Handle edit button click
    function sentEditAction(ncrId) {
        window.location.href = '/operator/ncr/' + ncrId + '/edit';
    }

    // Handle show button click
    function showRequestModal(ncrId) {
        $('#sentNcrId').val(ncrId);
        $('#requestModal').modal('show');
    }
    const csrfToken = '{{ csrf_token() }}';
    // Fungsi untuk menyetujui permintaan
    function approveRequest(id) {
        $.ajax({
            url: "{{ route('operator.ncr.approve', '') }}/" + id, // Menggunakan route Laravel
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
            url: "{{ route('operator.ncr.reject', '') }}/" + id, // Menggunakan route Laravel
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
</script>
@endsection