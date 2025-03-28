@extends('layouts.user_type.auth')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="container-fluid py-4">
        <!-- Requests Section -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>REQUESTS</h6>
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
                                            <td class="text-center">{{ $request->nama_pengirim }}</td>
                                            <td class="text-center">{{ $request->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center">{{ $request->jenis_pengajuan }}</td>
                                            <td class="text-center">{{ $request->alasan }}</td>
                                            <td class="text-center" id="status-{{$request->id}}">{{ $request->status }}</td>
                                            <td class="text-center">
                                                @if ($request->status == 'Pending')
                                                <button class="btn btn-success btn-sm" onclick="approveRequest('{{ $request->id }}')">Approve</button>
                                                <button class="btn btn-danger btn-sm" onclick="rejectRequest('{{ $request->id }}')">Reject</button>
                                                @endif
                                                <form action="{{ route('adminsystem.tool.show', ['id' => $request->id]) }}" method="GET" style="display:inline;">
                                                    <button type="submit" class="btn btn-primary btn-sm">Show</button>
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

        <br>
        <div class="nav-item d-flex align-self-end">
            <form action="{{ route('adminsystem.tool.create') }}" method="GET" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-primary active mb-0 text-white">Tambah</button>
            </form>
        </div>
        <br>

        <!-- Draft Tool & Equipment Section -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Draft Tool & Equipment</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <div class="card-header pb-0">
                                <table class="table align-items-center mb-0" id="draftToolTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alat Terpakai</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kondisi Fisik</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fungsi Kerja</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sertifikasi</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kebersihan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Label Petunjuk</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pemeliharaan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keamanan Pengguna</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Pemeriksaan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Catatan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tools as $tool)
                                        <tr>
                                            <td class="text-center">{{ $tool->alat_terpakai }}</td>
                                            <td class="text-center">{{ $tool->kondisi_fisik }}</td>
                                            <td class="text-center">{{ $tool->fungsi_kerja }}</td>
                                            <td class="text-center">{{ $tool->sertifikasi }}</td>
                                            <td class="text-center">{{ $tool->kebersihan }}</td>
                                            <td class="text-center">{{ $tool->label_petunjuk }}</td>
                                            <td class="text-center">{{ $tool->pemeliharaan }}</td>
                                            <td class="text-center">{{ $tool->keamanan_pengguna }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($tool->tanggal_pemeriksaan)->format('d/m/Y') }}</td>
                                            <td class="text-center">{{ $tool->catatan }}</td>
                                            <td class="align-middle text-center">
                                                <a href="javascript:;" id="editBtn" onclick="editAction('{{ $tool->id }}');" style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #28A745, #2E8B57); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('adminsystem.tool.destroy', $tool->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,rgb(167, 40, 40),rgb(139, 46, 46)); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;" onclick="return confirm('Anda yakin akan mengirim dokumen?')">Send</button>
                                                </form>
                                                <form action="{{ route('adminsystem.tool.show', ['id' => $tool->id]) }}" method="GET" style="display:inline;">
                                                    <button type="submit" style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #28A745, #2E8B57); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;" class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">Show</button>
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

        <!-- Sent Tool & Equipment Section -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Sent Tool & Equipment</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <div class="card-header pb-0">
                                <table class="table align-items-center mb-0" id="sentToolTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alat Terpakai</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kondisi Fisik</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fungsi Kerja</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sertifikasi</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kebersihan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Label Petunjuk</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pemeliharaan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keamanan Pengguna</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Pemeriksaan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Catatan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tool_fixs as $tool_fix)
                                        <tr>
                                            <td class="text-center">{{ $tool_fix->alat_terpakai }}</td>
                                            <td class="text-center">{{ $tool_fix->kondisi_fisik }}</td>
                                            <td class="text-center">{{ $tool_fix->fungsi_kerja }}</td>
                                            <td class="text-center">{{ $tool_fix->sertifikasi }}</td>
                                            <td class="text-center">{{ $tool_fix->kebersihan }}</td>
                                            <td class="text-center">{{ $tool_fix->label_petunjuk }}</td>
                                            <td class="text-center">{{ $tool_fix->pemeliharaan }}</td>
                                            <td class="text-center">{{ $tool_fix->keamanan_pengguna }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($tool_fix->tanggal_pemeriksaan)->format('d/m/Y') }}</td>
                                            <td class="text-center">{{ $tool_fix->catatan }}</td>
                                            <td class="text-center">
                                                @if ($tool_fix->status == 'Nothing')
                                                <button class="btn btn-success btn-sm" onclick="showRequestModal('{{ $tool_fix->id }}')">Request</button>
                                                @elseif ($tool_fix->status == 'Pending')
                                                <span class="text-warning">Pending</span>
                                                @elseif ($tool_fix->status == 'Approved')
                                                <span class="text-success">Approved</span>
                                                @elseif ($tool_fix->status == 'Rejected')
                                                <span class="text-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                    <form id="requestForm" method="POST" action="{{ route('adminsystem.tool.storeRequest') }}">
                        @csrf
                        <input type="hidden" id="sentNcrId" name="sent_tool_id">
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
</main>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize data tables
        $('#requestTable').DataTable({
            pageLength: 5,
            searching: true,
            ordering: true,
            responsive: true
        });

        $('#draftToolTable').DataTable({
            pageLength: 5,
            searching: true,
            ordering: true,
            responsive: true
        });

        $('#sentToolTable').DataTable({
            pageLength: 5,
            searching: true,
            ordering: true,
            responsive: true
        });
    });

    // Handle edit button click
    function sentEditAction(toolId) {
        window.location.href = '/adminsystem/tool/' + toolId + '/edit';
    }

    // Handle show button click
    function showRequestModal(toolId) {
        $('#sentNcrId').val(toolId);
        $('#requestModal').modal('show');
    }

    // Handle approve request
    function approveRequest(requestId) {
        $.ajax({
            url: '/adminsystem/tool/approve/' + requestId,
            type: 'GET',
            success: function(response) {
                $('#status-' + requestId).text('Approved');
            }
        });
    }

    // Handle reject request
    function rejectRequest(requestId) {
        $.ajax({
            url: '/adminsystem/tool/reject/' + requestId,
            type: 'GET',
            success: function(response) {
                $('#status-' + requestId).text('Rejected');
            }
        });
    }
</script>
@endsection