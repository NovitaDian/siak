@extends('layouts.user_type.operator')

@section('content')

<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2"
    onclick="window.location.href='{{ route('operator.home') }}'">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 200%; ">

        </div>
    </div>
    <!-- SENT DOCUMENT TABLE -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Tombol Tambah -->
                        <form action="{{ route('operator.ppe.create') }}" method="GET" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                        </form>

                        <!-- Tombol Export -->
                        <div class="col-12 col-md-3 d-flex justify-content-md-end justify-content-start align-items-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle w-md-auto" type="button" id="downloadDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-download me-1"></i> Unduh
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="downloadDropdown">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-file-excel text-success me-2"></i> Excel
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-file-pdf text-danger me-2"></i> PDF
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="card-header">
                    <form action="{{ route('operator.ppe.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="start_date">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-sm btn-warning">Filter</button>
                        </div>
                    </form>
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
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Report</th>
                                </tr>
                            </thead>
                            <tbody id="sentTableBody">
                                @forelse ($ppe_fixs as $ppe_fix)
                                <tr>
                                    <td class="text-center text-xs">{{ $loop->iteration }}</td>
                                    <td class="text-center text-xs">{{ \Carbon\Carbon::parse($ppe_fix->tanggal_shift_kerja)->format('d/m/Y') }}</td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm {{ $ppe_fix->status_ppe == 'Non-Compliant' ? 'bg-gradient-warning' : 'bg-gradient-success' }}">
                                            {{ $ppe_fix->status_ppe }}
                                        </span>
                                    </td>
                                    <td class="text-center text-xs">{{ $ppe_fix->shift_kerja }}</td>
                                    <td class="text-center text-xs">{{ $ppe_fix->inspector->name }}</td>
                                    <td class="text-center text-xs">{{ $ppe_fix->jam_mulai }}-{{ $ppe_fix->jam_selesai }}</td>
                                    <td class="text-center text-xs">{{ $ppe_fix->zona_pengawasan }}</td>
                                    <td class="text-center text-xs">{{ $ppe_fix->lokasi_observasi }}</td>
                                    <td class="align-middle text-center">
                                        @if ($ppe_fix->status == 'Nothing')
                                        <button class="btn btn-info btn-xs" onclick="showRequestModal('{{ $ppe_fix->id }}')">
                                            <i class="fas fa-paper-plane me-1" style="font-size: 12px;"></i> Request
                                        </button>

                                        @elseif ($ppe_fix->status == 'Pending')
                                        <span class="badge bg-warning text-dark">Pending</span>


                                        @elseif ($ppe_fix->status == 'Approved')
                                        @php
                                        $request = $latestRequests->firstWhere('sent_ppe_id', $ppe_fix->id);
                                        @endphp

                                        @if ($request)
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-success btn-xs dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-check-circle me-1" style="font-size: 12px;"></i> Approved
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if ($request->type == 'Edit')
                                                <li>
                                                    <a href="javascript:;" onclick="sentEditAction('{{ $ppe_fix->id }}');" class="dropdown-item">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>
                                                </li>
                                                @elseif ($request->type == 'Delete')
                                                <li>
                                                    <form action="{{ route('operator.ppe.sent_destroy', $ppe_fix->id) }}" method="POST" onsubmit="return confirm('Anda yakin akan menghapus data ini?')">
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
                                        <span class="text-danger">No corresponding request found</span>
                                        @endif

                                        @elseif ($ppe_fix->status == 'Rejected')
                                        <span class="badge bg-danger text-white">Rejected</span>
                                        @endif
                                    </td>

                                    <td class="align-middle text-center">
                                        @if ($ppe_fix->status_ppe == 'Non-Compliant')
                                        <form action="{{ route('operator.ppe.show', ['id' => $ppe_fix->id]) }}" method="GET" style="display:inline;">
                                            <button type="submit" class="btn btn-secondary btn-xs">
                                                <i class="fas fa-flag"></i>&nbsp; Report
                                            </button>
                                        </form>
                                        @endif

                                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                                    </td>

                                    @empty

                                    @endforelse
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
                    <form id="requestForm" method="POST" action="{{ route('operator.ppe.storeRequest') }}">
                        @csrf
                        <input type="hidden" id="sentppeId" name="sent_ppe_id">
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
                        <button type="submit" id="submitRequestBtn" class="btn btn-primary">Submit Request</button>

                        <script>
                            document.getElementById('requestForm').addEventListener('submit', function() {
                                const btn = document.getElementById('submitRequestBtn');
                                btn.disabled = true;
                                btn.innerText = 'Mengirim...'; // ubah teks saat loading
                            });
                        </script>
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

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

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
            url: "{{ route('operator.ppe.approve', '') }}/" + id, // Menggunakan route Laravel
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
            url: "{{ route('operator.ppe.reject', '') }}/" + id, // Menggunakan route Laravel
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
        window.location.href = "{{ url('operator/ppe/sent_edit') }}/" + id; // Menggunakan URL Laravel
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