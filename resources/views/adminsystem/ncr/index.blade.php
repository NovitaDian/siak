@extends('layouts.user_type.auth')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="container-fluid py-4">

    </div>

    <br>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>REQUEST</h6>
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
                                    @foreach ($allRequests as $request)
                                    <tr>
                                        <td class="text-center text-xs">{{ $request->nama_pengirim }}</td>
                                        <td class="text-center text-xs">{{ $request->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center text-xs">{{ $request->type }}</td>
                                        <td class="text-center text-xs">{{ $request->reason }}</td>
                                        <td class="text-center" id="status-{{$request->id}}">{{ $request->status }}</td>
                                        <td class="text-center">
                                            @if ($request->status == 'Pending')
                                            <form action="{{ route('adminsystem.ncr.approve', $request->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-xs">Approve</button>
                                            </form>

                                            <form action="{{ route('adminsystem.ncr.reject', $request->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-xs">Reject</button>
                                            </form>
                                            @endif
                                            <form action="{{ route('adminsystem.ncr.show', ['id' => $request->sent_ncr_id]) }}" method="GET" style="display:inline;">
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
                    <h6 class="mb-0">Draft NCR</h6>
                    <form action="{{ route('adminsystem.ncr.create') }}" method="GET" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">
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
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
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
                                        <td class="align-middle text-center text-sm">
                                            <span class="badge badge-sm {{ $ncr->status_ncr == 'Closed' ? 'bg-gradient-warning' : 'bg-gradient-success' }}">
                                                {{ $ncr->status_ncr == 'Closed' ? 'Closed' : 'Open' }}
                                            </span>
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
                                            <div style="display: flex; justify-content: center; gap: 6px;">
                                                <a href="{{ route('adminsystem.ncr.edit', $ncr->id) }}" class="btn btn-warning">
                                                    <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit
                                                </a>

                                                <form action="{{ route('adminsystem.ncr.destroy', $ncr->id) }}" method="POST" style="margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-primary"
                                                        onclick="return confirm('Anda yakin akan mengirim dokumen?')"
                                                        title="Kirim"> <i class="fas fa-paper-plane me-1" style="margin-right: 4px; font-size: 12px;"></i> Send
                                                    </button>
                                                </form>


                                            </div>
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
                    <form action="{{ route('adminsystem.ncr.index') }}" method="GET" class="row g-3 px-4 mb-3">
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
                            <a href="{{ route('adminsystem.ncr.export', request()->all()) }}" class="btn btn-sm btn-success w-100 w-md-auto">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </a>
                            <a href="{{ route('adminsystem.ncr.exportPdf', request()->all()) }}" class="btn btn-sm btn-danger w-100 w-md-auto">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center mb-0" id="sentNcrTable">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Shift Kerja</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shift Kerja</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HSE Inspector</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Audit</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Auditee</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Durasi NCR</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ststus</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ncr_fixs as $ncr_fix)
                                <tr class="sent-ncr-row" data-ncr-id="{{ $ncr_fix->id }}">
                                    <td class="text-center text-xs">{{ \Carbon\Carbon::parse($ncr_fix->tanggal_shift_kerja)->format('d/m/Y') }}</td>
                                    <td class="text-center text-xs">{{ $ncr_fix->shift_kerja }}</td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm {{ $ncr_fix->status_ncr == 'Closed' ? 'bg-gradient-warning' : 'bg-gradient-success' }}">
                                            {{ $ncr_fix->status_ncr == 'Closed' ? 'Closed' : 'Open' }}
                                        </span>
                                    </td>
                                    <td class="text-center text-xs">{{ $ncr_fix->nama_hs_officer_1 }}</td>
                                    <td class="text-center text-xs">{{ \Carbon\Carbon::parse($ncr_fix->tanggal_audit)->format('d/m/Y') }}</td>
                                    <td class="text-center text-xs">{{ $ncr_fix->nama_auditee }}</td>
                                    <td class="text-center text-xs">{{ $ncr_fix->durasi_ncr }}</td>
                                    <td class="text-center text-xs">{{ $ncr_fix->status }}</td>
                                    <td class="align-middle text-center text-xs">
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            {{-- Ambil request yang cocok --}}
                                            @php
                                            $matchedRequest = $allRequests->firstWhere('sent_ncr_id', $ncr_fix->id);
                                            $isDeleteRequest = $matchedRequest && $matchedRequest->type === 'Delete';
                                            $isEditRequest = $matchedRequest && $matchedRequest->type === 'Edit';
                                            @endphp

                                            {{-- STATUS: Nothing --}}
                                            @if ($ncr_fix->status === 'Nothing')
                                            <button class="btn btn-secondary btn-xs" onclick="showRequestModal('{{ $ncr_fix->id }}')">
                                                Request
                                            </button>

                                            {{-- STATUS: Pending --}}
                                            @elseif ($ncr_fix->status === 'Pending')
                                            <span class="text-warning align-self-center">Pending</span>

                                            {{-- STATUS: Approved --}}
                                            @elseif ($ncr_fix->status === 'Approved')

                                            {{-- DELETE REQUEST --}}
                                            @if ($isDeleteRequest)
                                            <form action="{{ route('adminsystem.ncr.sent_destroy', $ncr_fix->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Anda yakin akan menghapus data ini?')">
                                                    Delete
                                                </button>
                                            </form>

                                            {{-- EDIT REQUEST --}}
                                            @elseif ($isEditRequest)
                                            @if ($ncr_fix->status_ncr === 'Closed')
                                            <a href="{{ route('adminsystem.ncr.edit_closed', $ncr_fix->id) }}" class="btn btn-warning btn-xs">
                                                <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit
                                            </a>
                                            @elseif ($ncr_fix->status_ncr === 'Open')
                                            <a href="{{ route('adminsystem.ncr.sent_edit', $ncr_fix->id) }}" class="btn btn-warning btn-xs">
                                                <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit
                                            </a>
                                            @endif
                                            @endif

                                            {{-- STATUS: Rejected --}}
                                            @elseif ($ncr_fix->status === 'Rejected')
                                            <span class="text-danger align-self-center">Request Rejected</span>
                                            @endif

                                            {{-- Tombol Close atau Show tetap ditampilkan terlepas dari request --}}
                                            @if ($ncr_fix->status_ncr === 'Open')
                                            <a href="{{ route('adminsystem.ncr.close', $ncr_fix->id) }}" class="btn btn-secondary btn-sm"
                                                style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #6c757d, #5a6268); color: white; border-radius: 5px; font-weight: bold; font-size: 10px;">
                                                Close
                                            </a>
                                            @elseif ($ncr_fix->status_ncr === 'Closed')
                                            <form action="{{ route('adminsystem.ncr.show', ['id' => $ncr_fix->id]) }}" method="GET" class="m-0">
                                                <button type="submit" class="btn btn-info btn-xs d-flex align-items-center">
                                                    <i class="fas fa-eye me-1" style="font-size: 12px;"></i> Show
                                                </button>
                                            </form>
                                            @endif
                                        </div>
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
                <form id="requestForm" method="POST" action="{{ route('adminsystem.ncr.storeRequest') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="requestModalLabel">Request Edit/Delete</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="sentNcrId" name="sent_ncr_id">
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

<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
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
        window.location.href = '/adminsystem/ncr/' + ncrId + '/edit';
    }

    // Handle show button click
    function showRequestModal(ncrId) {
        $('#sentNcrId').val(ncrId);
        $('#requestModal').modal('show');
    }

    // Handle approve request
    function approveRequest(requestId) {
        $.ajax({
            url: '/adminsystem/ncr/approve/' + requestId,
            type: 'GET',
            success: function(response) {
                $('#status-' + requestId).text('Approved');
            }
        });
    }

    // Handle reject request
    function rejectRequest(requestId) {
        $.ajax({
            url: '/adminsystem/ncr/reject/' + requestId,
            type: 'GET',
            success: function(response) {
                $('#status-' + requestId).text('Rejected');
            }
        });
    }
</script>
@endsection