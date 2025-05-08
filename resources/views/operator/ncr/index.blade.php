@extends('layouts.user_type.operator')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="container-fluid py-4">
        <div class="nav-item d-flex align-self-end">
            <form action="{{ route('operator.ncr.create') }}" method="GET" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">
                    Tambah
                </button>
            </form>
        </div>
    </div>

    <br>


    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Draft NCR</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
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
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ \Carbon\Carbon::parse($ncr->tanggal_shift_kerja)->format('d/m/Y') }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $ncr->shift_kerja }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $ncr->nama_hs_officer_1 }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ \Carbon\Carbon::parse($ncr->tanggal_audit)->format('d/m/Y') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $ncr->nama_auditee }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="javascript:;" id="editBtn" onclick="editAction({{ $ncr->id }});" style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #28A745, #2E8B57); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('operator.ncr.destroy', $ncr->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,rgb(167, 40, 40),rgb(139, 46, 46)); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;" onclick="return confirm('Anda yakin akan mengirim dokumen?')">Send</button>
                                        </form>
                                        <form action="{{ route('operator.ncr.show', ['id' => $ncr->id]) }}" method="GET" style="display:inline;">
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

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Sent NCR</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Shift Kerja</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shift Kerja</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HSE Inspector</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Audit</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Auditee</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ncr_fixs as $ncr_fix)
                                <tr class="sent-ncr-row" data-ncr-id="{{ $ncr_fix->id }}">
                                    <td class="text-center">{{ \Carbon\Carbon::parse($ncr_fix->tanggal_shift_kerja)->format('d/m/Y') }}</td>
                                    <td class="text-center">{{ $ncr_fix->shift_kerja }}</td>
                                    <td class="text-center">{{ $ncr_fix->nama_hs_officer_1 }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($ncr_fix->tanggal_audit)->format('d/m/Y') }}</td>
                                    <td class="text-center">{{ $ncr_fix->nama_auditee }}</td>
                                    <td class="align-middle text-center">
                                        @if ($ncr_fix->status == 'Nothing')
                                        <button class="btn btn-success btn-sm" style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,rgb(154, 155, 160),rgb(43, 46, 44)); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;" onclick="showRequestModal('{{ $ncr_fix->id }}')">Request</button>
                                        @elseif ($ncr_fix->status == 'Pending')
                                        <span class="text-warning">Pending</span>
                                        @elseif ($ncr_fix->status == 'Approved')
                                        @php
                                        // Fetch the corresponding request based on sent_ncr_id
                                        $request = $requests->firstWhere('sent_ncr_id', $ncr_fix->id);
                                        \Log::info('NCR Fix ID: ' . $ncr_fix->id . ' Looking for Request with sent_ncr_id: ' . $ncr_fix->id);
                                        \Log::info('Requests: ', $requests->toArray());
                                        @endphp
                                        @if ($request)
                                        @if ($request->type == 'Edit')
                                        <a href="javascript:;" id="editBtn" onclick="sentEditAction({{ $ncr_fix->id }});" style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #28A745, #2E8B57); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;" class="btn btn-warning btn-sm">Edit</a>
                                        @elseif ($request->type == 'Delete')
                                        <form action="{{ route('operator.ncr.sent_destroy', $ncr_fix->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,rgb(167, 40, 40),rgb(139, 46, 46)); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;" onclick="return confirm(' Anda yakin akan menghapus data ini?')">Delete</button>
                                        </form>
                                        @endif
                                        @else
                                        <span class="text-danger">No corresponding request found</span>
                                        @endif
                                        @elseif ($ncr_fix->status == 'Rejected')
                                        <span class="text-danger">Request Rejected</span>
                                        @endif
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


    <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalLabel">Request Edit/Delete</h5>
                </div>
                <div class="modal-body">
                    <form id="requestForm" method="POST" action="{{ route('operator.ncr.storeRequest') }}">
                        @csrf
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

<script>
    function showRequestModal(id) {
        $('#sentNcrId').val(id);
        $('#requestModal').modal('show');
    }

    function approveRequest(id) {
        $.ajax({
            url: '/operator/ncr/approve/' + id,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    $('#status-' + id).text('Approved');
                } else {
                    console.error("Approval failed:", response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error approving request:", error);
            }
        });
    }

    function rejectRequest(id) {
        $.ajax({
            url: '/operator/ncr/reject/' + id,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#status-' + id).text('Rejected');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error rejecting request:", error);
            }
        });
    }

    function editAction(id) {
        window.location.href = "{{ url('operator/ncr/edit') }}/" + id;
    }

    function sentEditAction(id) {
        window.location.href = "{{ url('operator/ncr/sent_edit') }}/" + id;
    }
</script>

@endsection