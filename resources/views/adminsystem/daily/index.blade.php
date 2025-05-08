@extends('layouts.user_type.auth')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
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
                                        <td class="text-center text-xs">
                                            <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                                                @if ($request->status == 'Pending')
                                                <button
                                                    class="btn btn-success btn-sm"
                                                    style="padding: 4px 8px; background: linear-gradient(to right, #28A745, #2E8B57); color: white; border-radius: 5px; font-weight: bold; font-size: 10px;"
                                                    onclick="approveRequest('{{ $request->id }}')">
                                                    Approve
                                                </button>
                                                <button
                                                    class="btn btn-danger btn-sm"
                                                    style="padding: 4px 8px; background: linear-gradient(to right,rgb(167, 40, 40),rgb(139, 46, 46)); color: white; border-radius: 5px; font-weight: bold; font-size: 10px;"
                                                    onclick="rejectRequest('{{ $request->id }}')">
                                                    Reject
                                                </button>
                                                @endif
                                                <form
                                                    action="{{ route('adminsystem.daily.show', ['id' => $request->id]) }}"
                                                    method="GET"
                                                    style="margin: 0;">
                                                    <button
                                                        type="submit"
                                                        style="padding: 4px 8px; background: linear-gradient(to right,rgb(67, 116, 206),rgb(46, 54, 139)); color: white; border-radius: 5px; font-weight: bold; font-size: 10px;"
                                                        class="btn btn-primary btn-sm text-white">
                                                        Show
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
        <br>

        <br>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Draft Daily Activities & Improvement Report</h6>
                        <form action="{{ route('adminsystem.daily.create') }}" method="GET">
                            @csrf
                            <button
                                type="submit"
                                class="btn btn-primary active text-white"
                                role="button"
                                aria-pressed="true">
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
                                            <p class="text-xs font-weight-bold mb-0">{{ $daily->rincian_laporan }}</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">

                                                <!-- Tombol Edit -->
                                                <a href="{{ route('adminsystem.daily.edit', $daily->id) }}"
                                                    style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #FFA500, #FF6347); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px; margin-right: 8px;">
                                                    <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit

                                                </a>

                                                <!-- Tombol Send -->
                                                <form action="{{ route('adminsystem.daily.destroy', $daily->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('Anda yakin akan mengirim dokumen?')"
                                                        style="display: inline-flex; align-items: center; justify-content: center; padding: 4px 8px;  background: linear-gradient(to right, #28A745, #2E8B57); color: white; border: none; border-radius: 5px; font-weight: bold; font-size: 10px;">
                                                        <i style="margin-right: 4px; font-size: 12px;"></i> Send
                                                    </button>
                                                </form>
                                            </div>
                                        </td>


                                        <script>
                                            function editAction() {
                                                // Redirect to the edit form for the item
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
                                                <p class="text-xs font-weight-bold mb-0">{{ $daily_fix->rincian_laporan }}</p>
                                            </td>


                                            <td class="align-middle text-center">
                                                <div style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 6px;">
                                                    @if ($daily_fix->status == 'Nothing')
                                                    <button onclick="showRequestModal('{{ $daily_fix->id }}')"
                                                        style="display: flex; align-items: center; gap: 4px; padding: 4px 8px; background: linear-gradient(to right, #9a9ba0, #2b2e2c); color: white; border: none; border-radius: 5px; font-size: 10px; font-weight: bold;">
                                                        <i style="font-size: 12px;"></i>Request
                                                    </button>
                                                    @elseif ($daily_fix->status == 'Pending')
                                                    <span style="display: flex; align-items: center; justify-content: center; padding: 4px 8px; background: #ffc107; color: #212529; border-radius: 5px; font-size: 10px; font-weight: bold;">
                                                        Pending
                                                    </span>
                                                    @elseif ($daily_fix->status == 'Approved')
                                                    @php
                                                    $request = $requests->firstWhere('sent_daily_id', $daily_fix->id);
                                                    @endphp
                                                    @if ($request)
                                                    @if ($request->type == 'Edit')
                                                    <a href="javascript:;" onclick="sentEditAction('{{ $daily_fix->id }}')"
                                                        style="display: flex; align-items: center; gap: 4px; padding: 4px 8px; background: linear-gradient(to right, #28A745, #2E8B57); color: white; border-radius: 5px; font-size: 10px; font-weight: bold;">
                                                        <i style="font-size: 12px;"></i>Edit
                                                    </a>
                                                    @elseif ($request->type == 'Delete')
                                                    <form action="{{ route('adminsystem.daily.sent_destroy', $daily_fix->id) }}" method="POST" style="margin: 0;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm(' Anda yakin akan menghapus data ini?')"
                                                            style="display: flex; align-items: center; gap: 4px; padding: 4px 8px; background: linear-gradient(to right, #a72828, #8b2e2e); color: white; border: none; border-radius: 5px; font-size: 10px; font-weight: bold;">
                                                            <i style="font-size: 12px;"></i>Delete
                                                        </button>
                                                    </form>
                                                    @endif
                                                    @else
                                                    <span style="display: flex; align-items: center; justify-content: center; padding: 4px 8px; background: #dc3545; color: white; border-radius: 5px; font-size: 10px; font-weight: bold;">
                                                        No Request
                                                    </span>
                                                    @endif
                                                    @elseif ($daily_fix->status == 'Rejected')
                                                    <span style="display: flex; align-items: center; justify-content: center; padding: 4px 8px; background: #dc3545; color: white; border-radius: 5px; font-size: 10px; font-weight: bold;">
                                                        Rejected
                                                    </span>
                                                    @endif

                                                    {{-- Show button (selalu tampil) --}}
                                                    <a href="{{ route('adminsystem.daily.show', $daily_fix->id) }}"
                                                        style="display: flex; align-items: center; gap: 4px; padding: 4px 8px; background: linear-gradient(to right, #4374ce, #2e368b); color: white; border-radius: 5px; font-size: 10px; font-weight: bold;">
                                                        <i style="font-size: 12px;"></i> Show
                                                    </a>
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