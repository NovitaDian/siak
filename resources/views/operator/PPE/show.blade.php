@extends('layouts.user_type.operator')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
    

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
                <form action="{{ route('operator.non_compliant.create', $ppeFix->id) }}" method="GET" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
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
                                        <button class="btn btn-secondary btn-xs" onclick="showRequestModal('{{ $nc->id }}')">
                                            <i class="fas fa-paper-plane me-1" style="font-size: 12px;"></i> Request
                                        </button>

                                        @elseif ($nc->status == 'Pending')
                                        <span class="badge bg-warning text-dark">Pending</span>


                                        @elseif ($nc->status == 'Approved')
                                        @if ($request)
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-success btn-xs dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-check-circle me-1" style="font-size: 12px;"></i> Approved
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if ($request->type == 'Edit')
                                                <li>
                                                    <a href="{{ route('operator.non_compliant.edit', $nc->id) }}" class="dropdown-item">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>
                                                </li>
                                                @elseif ($request->type == 'Delete')
                                                <li>
                                                    <form action="{{ route('operator.non_compliant.destroy', $nc->id) }}" method="POST" onsubmit="return confirm('Anda yakin akan menghapus data ini?')">
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

                                        @elseif ($nc->status == 'Rejected')
                                        <span class="badge bg-danger text-white">Rejected</span>
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
                            <form id="requestForm" method="POST" action="{{ route('operator.non_compliant.storeRequest') }}">
                                @csrf
                                <input type="hidden" id="sentNonCompliantId" name="sent_non_compliant_id">
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
            url: "{{ route('operator.non_compliant.approve', '') }}/" + id, // Menggunakan route Laravel
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
            url: "{{ route('operator.non_compliant.reject', '') }}/" + id, // Menggunakan route Laravel
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
        window.location.href = "{{ url('operator/non_compliant/edit') }}/" + id; // Menggunakan URL Laravel
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