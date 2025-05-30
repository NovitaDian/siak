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
                                            <td class="text-center text-xs">{{ $request->nama_pengirim }}</td>
                                            <td class="text-center text-xs">{{ $request->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center text-xs">{{ $request->type }}</td>
                                            <td class="text-center text-xs">{{ $request->reason }}</td>
                                            <td class="text-center" id="status-{{$request->id}}">{{ $request->status }}</td>
                                            <td class="text-center">
                                                @if ($request->status == 'Pending')
                                                <button class="btn btn-primary btn-xs" onclick="approveRequest('{{ $request->id }}')">Approve</button>
                                                <button class="btn btn-danger btn-xs" onclick="rejectRequest('{{ $request->id }}')">Reject</button>
                                                @endif
                                                <form action="{{ route('adminsystem.non_compliant.show', ['id' => $request->id]) }}" method="GET" style="display:inline;">
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

        <div class="row"></div>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center pb-0">
                <h6 class="mb-0">REPORT NON COMPLIANT</h6>

            </div>

            <div class="card-body px-4 pt-4 pb-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Tanggal Shift Kerja:</strong><br>
                        {{ \Carbon\Carbon::parse($ppeFix->tanggal_shift_kerja)->format('d/m/Y') }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Shift Kerja:</strong><br>
                        {{ $ppeFix->shift_kerja }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Safety Officer:</strong><br>
                        {{ $ppeFix->nama_hse_inspector }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Jam Pengawasan:</strong><br>
                        {{ $ppeFix->jam_mulai }} - {{ $ppeFix->jam_selesai }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Zona Pengawasan:</strong><br>
                        {{ $ppeFix->zona_pengawasan }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Lokasi Observasi:</strong><br>
                        {{ $ppeFix->lokasi_observasi }}
                    </div>
                </div>
            </div>


            <div class="card-header d-flex justify-content-between align-items-center pb-0">
                <h6 class="mb-0"></h6>
                <form action="{{ route('adminsystem.non_compliant.create', $ppeFix->id) }}" method="GET" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <div class="card-header pb-0">
                        <table class="table align-items-center mb-0" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Pelanggar</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deskripsi Ketidaksesuaian</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Perusahaan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Bagian</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tindakan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="draftTableBody">
                                @forelse ($nonCompliants as $nc)
                                <tr>
                                    <td class="text-center text-xs">{{ $loop->iteration }}</td>
                                    <td class="text-center text-xs">{{ $nc->nama_pelanggar }}</td>
                                    <td class="text-center text-xs">{{ $nc->deskripsi_ketidaksesuaian }}</td>
                                    <td class="text-center text-xs">{{ $nc->perusahaan }}</td>
                                    <td class="text-center text-xs">{{ $nc->nama_bagian }}</td>
                                    <td class="text-center text-xs">{{ $nc->tindakan }}</td>
                                    <td class="align-middle text-center text-xs">
                                        @if ($nc->status == 'Nothing')
                                        <button class="btn btn-secondary btn-xs" onclick="showRequestModal('{{ $nc->id }}')"> <i></i>&nbsp; Request
                                        </button>
                                        @elseif ($nc->status == 'Pending')
                                        <span class="text-warning">Pending</span>

                                        @elseif ($nc->status == 'Approved')
                                        @if ($request)
                                        @if ($request->type == 'Edit')
                                        <a href="{{ route('adminsystem.non_compliant.edit', $nc->id) }}" class="btn btn-warning">
                                            <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit
                                        </a>
                                        @elseif ($request->type == 'Delete')
                                        <form action="{{ route('adminsystem.non_compliant.destroy', $nc->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right,rgb(167, 40, 40),rgb(139, 46, 46)); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;"
                                                onclick="return confirm(' Anda yakin akan menghapus data ini?')">Delete</button>
                                        </form>
                                        @endif
                                        @endif

                                        @elseif ($nc->status == 'Rejected')
                                        <span class="text-danger">Request Rejected</span>
                                        @endif

                                    </td>

                                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13" class="text-center text-muted">Data tidak ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                            <form id="requestForm" method="POST" action="{{ route('adminsystem.non_compliant.storeRequest') }}">
                                @csrf
                                <input type="hidden" id="sentNonCompliantId" name="sent_non_compliant_id">
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
        $('#sentNonCompliantId').val(id);
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
            url: "{{ route('adminsystem.non_compliant.approve', '') }}/" + id, // Menggunakan route Laravel
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
            url: "{{ route('adminsystem.non_compliant.reject', '') }}/" + id, // Menggunakan route Laravel
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
        window.location.href = "{{ url('adminsystem/non_compliant/edit') }}/" + id; // Menggunakan URL Laravel
    }
</script>

<script>
    $(document).ready(function() {
        $('#requestTable').DataTable({
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
        $('#dataTable').DataTable({
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