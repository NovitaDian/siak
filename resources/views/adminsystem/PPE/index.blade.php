@extends('layouts.user_type.auth')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
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
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Pengirim</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu Pengajuan</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Pengajuan</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alasan</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="notificationTableBody">
                                    @forelse ($requests as $request)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="text-center text-xs">{{ $loop->iteration }}</td>
                                        <td class="text-center text-xs text-gray-900 dark:text-white">{{ $request->nama_pengirim }}</td>
                                        <td class="text-center text-xs">{{ $request->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center text-xs">{{ $request->type }}</td>
                                        <td class="text-center text-xs">{{ $request->reason }}</td>
                                        <td class="text-center text-xs" id="status-{{$request->id}}">{{ $request->status }}</td>
                                        <td class="text-center text-xs">
                                            @if ($request->status == 'Pending')
                                            <button class="btn btn-success btn-sm" style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,rgb(167, 40, 40),rgb(139, 46, 46)); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;" onclick="approveRequest('{{ $request->id }}')">Approve</button>
                                            <button class="btn btn-danger btn-sm" style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #28A745, #2E8B57); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;" onclick="rejectRequest('{{ $request->id }}')">Reject</button>
                                            @endif
                                            <form action="{{ route('adminsystem.ppe.show', ['id' => $request->id]) }}" method="GET" style="display:inline;">
                                                <button type="submit" style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,rgb(67, 116, 206),rgb(46, 54, 139)); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;" class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">
                                                    Show
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Data tidak ditemukan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- DRAFT TABLE -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center pb-0">
                <h6 class="mb-0">Draft</h6>
                <form action="{{ route('adminsystem.ppe.create') }}" method="GET" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>

            <div class="card-body px-4 pt-4 pb-4">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="dataTableDraft">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Shift Kerja</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shift Kerja</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Safety Officer</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jam Pengawasan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Zona Pengawasan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lokasi Observasi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="draftTableBody">
                            @forelse ($ppes as $ppe)
                            <tr>
                                <td class="text-center text-xs">{{ $loop->iteration }}</td>
                                <td class="text-center text-xs">{{ \Carbon\Carbon::parse($ppe->tanggal_shift_kerja)->format('d/m/Y') }}</td>
                                <td class="text-center text-xs">{{ $ppe->shift_kerja }}</td>
                                <td class="text-center text-xs">{{ $ppe->nama_hse_inspector }}</td>
                                <td class="text-center text-xs">{{ $ppe->jam_pengawasan }}</td>
                                <td class="text-center text-xs">{{ $ppe->zona_pengawasan }}</td>
                                <td class="text-center text-xs">{{ $ppe->lokasi_observasi }}</td>
                                <td class="text-center text-xs">
                                    <a href="javascript:;" onclick="editAction('{{ $ppe->id }}');" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('adminsystem.ppe.destroy', $ppe->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Anda yakin akan mengirim dokumen?')">Send</button>
                                    </form>
                                </td>
                                @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Data tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SENT DOCUMENT TABLE -->
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Sent Document</h6>
            </div>


            <div class="card-body px-4 pt-4 pb-4">
                <div class="table-responsive p-0">


                    <table class="table align-items-center mb-0" id="dataTableSent">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Shift Kerja</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shift Kerja</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HSE Inspector</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jam Pengawasan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Zona Pengawasan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lokasi Observasi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                            </tr>
                        </thead>
                        <tbody id="sentTableBody">
                            @forelse ($ppe_fixs as $ppe_fix)
                            <tr>
                                <td class="text-center text-xs">{{ $loop->iteration }}</td>
                                <td class="text-center text-xs">{{ \Carbon\Carbon::parse($ppe_fix->tanggal_shift_kerja)->format('d/m/Y') }}</td>
                                <td class="align-middle text-center text-sm">
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm {{ $ppe_fix->status_ppe == 'Non-Compliant' ? 'bg-gradient-warning' : 'bg-gradient-success' }}">
                                        {{ $ppe_fix->status_ppe }}
                                    </span>
                                </td>

                                </td>
                                <td class="text-center text-xs">{{ $ppe_fix->shift_kerja }}</td>
                                <td class="text-center text-xs">{{ $ppe_fix->nama_hse_inspector }}</td>
                                <td class="text-center text-xs">{{ $ppe_fix->jam_pengawasan }}</td>
                                <td class="text-center text-xs">{{ $ppe_fix->zona_pengawasan }}</td>
                                <td class="text-center text-xs">{{ $ppe_fix->lokasi_observasi }}</td>
                                <td class="align-middle text-center">
                                    @if ($ppe_fix->status == 'Nothing')
                                    <button class="btn btn-success btn-sm" style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,rgb(154, 155, 160),rgb(43, 46, 44)); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;" onclick="showRequestModal('{{ $ppe_fix->id }}')">Request</button>
                                    @elseif ($ppe_fix->status == 'Pending')
                                    <span class="text-warning">Pending</span>
                                    @elseif ($ppe_fix->status == 'Approved')
                                    @php
                                    $request = $requests->firstWhere('sent_ppe_id', $ppe_fix->id);
                                    \Log::info('Incident Fix ID: ' . $ppe_fix->id . ' Looking for Request with sent_ppe_id: ' . $ppe_fix->id);
                                    \Log::info('Requests: ', $requests->toArray());
                                    @endphp
                                    @if ($request)
                                    @if ($request->type == 'Edit')
                                    <a href="javascript:;" id="editBtn" onclick="sentEditAction('{{ $ppe_fix->id }}');" style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #28A745, #2E8B57); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;" class="btn btn-warning btn-sm">Edit</a>
                                    @elseif ($request->type == 'Delete')
                                    <form action="{{ route('adminsystem.ppe.sent_destroy', $ppe_fix->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,rgb(167, 40, 40),rgb(139, 46, 46)); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;" onclick="return confirm(' Anda yakin akan menghapus data ini?')">Delete</button>
                                    </form>
                                    @endif
                                    @else
                                    <span class="text-danger">No corresponding request found</span>
                                    @endif
                                    @elseif ($ppe_fix->status == 'Rejected')
                                    <span class="text-danger">Request Rejected</span>
                                    @endif

                                    @if ($ppe_fix->status_ppe == 'Non-Compliant')
                                    <a href="{{ route('adminsystem.non_compliant.create', $ppe_fix->id) }}"
                                        class="btn btn-primary btn-sm"
                                        style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,#007bff,#0056b3); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px; margin-top: 5px;">
                                        <i class="fas fa-flag"></i>&nbsp; Report
                                    </a>
                                    @endif

                                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                                </td>

                                @empty
                            <tr class="bg-white dark:bg-gray-800">
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Data tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
                    <form id="requestForm" method="POST" action="{{ route('adminsystem.ppe.storeRequest') }}">
                        @csrf
                        <input type="hidden" id="sentppeId" name="sent_ppe_id">
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
        $('#sentppeId').val(id);
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
            url: "{{ route('adminsystem.ppe.approve', '') }}/" + id, // Menggunakan route Laravel
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
            url: "{{ route('adminsystem.ppe.reject', '') }}/" + id, // Menggunakan route Laravel
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

    // Fungsi untuk mengedit item
    function editAction(id) {
        window.location.href = "{{ url('adminsystem/ppe/edit') }}/" + id; // Menggunakan URL Laravel
    }

    // Fungsi untuk mengedit item yang telah dikirim
    function sentEditAction(id) {
        window.location.href = "{{ url('adminsystem/ppe/sent_edit') }}/" + id; // Menggunakan URL Laravel
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