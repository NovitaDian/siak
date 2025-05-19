@extends('layouts.user_type.operator')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 200%; ">

                   </div>
    </div>


    <!-- Font Awesome CDN (if not already included elsewhere) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">




    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Draft</h6>
                    <form action="{{ route('operator.incident.create') }}" method="GET" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm text-white">
                            Tambah
                        </button>
                    </form>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Shift Kerja</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shift Kerja</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HSE InspectorI</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ada Kejadian</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Klasifikasi</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Korban</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($incidents as $incident)
                                <tr>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ \Carbon\Carbon::parse($incident->shift_date)->format('d/m/Y') }}</h6>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $incident->shift }}</p>
                                    </td>

                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $incident->safety_officer_1 }}</p>
                                    </td>

                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm {{ $incident->status_kejadian == 'Ada' ? 'bg-gradient-warning' : 'bg-gradient-success' }}">
                                            {{ $incident->status_kejadian == 'Ada' ? 'Ada' : 'Tidak' }}
                                        </span>
                                    </td>

                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $incident->klasifikasi_kejadiannya }}</span>
                                    </td>

                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $incident->ada_korban }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('operator.incident.edit', $incident->id) }}" class="btn btn-warning">
                                                <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit
                                            </a>

                                            <!-- Tombol Send -->
                                            <form action="{{ route('operator.incident.destroy', $incident->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-primary btn-xs d-flex align-items-center"
                                                    onclick="return confirm('Anda yakin akan mengirim dokumen?')"
                                                    title="Kirim">
                                                    <i class="fas fa-paper-plane me-1" style="font-size: 12px;"></i> Send
                                                </button>
                                            </form>


                                            <!-- Tombol Show -->
                                            <form action="{{ route('operator.incident.show', ['id' => $incident->id]) }}" method="GET" class="m-0">
                                                <button type="submit" class="btn btn-info btn-xs d-flex align-items-center">
                                                    <i class="fas fa-eye me-1" style="font-size: 12px;"></i> Show
                                                </button>
                                            </form>

                                        </div>

                                        <!-- Font Awesome (pindahkan ke layout utama jika belum global) -->
                                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                                    </td>



                                    <script>
                                        function editAction() {
                                            // Redirect to the edit form for the item
                                            window.location.href = "{{ route('operator.incident.edit', $incident->id) }}";
                                        }
                                    </script>
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
                    <h6>Sent Document</h6>
                    <br>
                    <form action="{{ route('operator.incident.index') }}" method="GET" class="row g-3 px-4 mb-3">
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
                            <a href="{{ route('operator.incident.export', request()->all()) }}" class="btn btn-sm btn-success w-100 w-md-auto">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </a>
                            <a href="{{ route('operator.incident.exportPdf', request()->all()) }}" class="btn btn-sm btn-danger w-100 w-md-auto">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </a>
                        </div>
                    </form>


                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Shift Kerja</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shift Kerja</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HSE InspectorI</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ada Kejadian</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Klasifikasi</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Korban</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($incident_fixs as $incident_fix)
                                <tr>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ \Carbon\Carbon::parse($incident_fix->shift_date)->format('d/m/Y') }}</h6>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $incident_fix->shift }}</p>
                                    </td>

                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $incident_fix->safety_officer_1 }}</p>
                                    </td>

                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm {{ $incident_fix->status_kejadian == 'Ada' ? 'bg-gradient-warning' : 'bg-gradient-success' }}">
                                            {{ $incident_fix->status_kejadian == 'Ada' ? 'Ada' : 'Tidak' }}
                                        </span>
                                    </td>

                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $incident_fix->klasifikasi_kejadiannya }}</span>
                                    </td>

                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $incident_fix->ada_korban }}</span>
                                    </td>

                                    <td class="align-middle text-center">
                                        @if ($incident_fix->status_request == 'Nothing')
                                        <button class="btn btn-secondary btn-xs" onclick="showRequestModal('{{ $incident_fix->id }}')"> <i></i>&nbsp; Request
                                            @elseif ($incident_fix->status_request == 'Pending')
                                            <span class="text-warning">Pending</span>
                                            @elseif ($incident_fix->status_request == 'Approved')
                                            @php
                                            // Fetch the corresponding request based on sent_incident_id
                                            $request = $requests->firstWhere('sent_incident_id', $incident_fix->id);
                                            \Log::info('Incident Fix ID: ' . $incident_fix->id . ' Looking for Request with sent_incident_id: ' . $incident_fix->id);
                                            \Log::info('Requests: ', $requests->toArray());
                                            @endphp
                                            @if ($request)
                                            @if ($request->type == 'Edit')
                                            <a href="{{ route('operator.incident.sent_edit', $incident_fix->id) }}" class="btn btn-warning">
                                                <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit
                                            </a>
                                            @elseif ($request->type == 'Delete')
                                            <form action="{{ route('operator.incident_fix.destroy', $incident_fix->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-danger btn-xs d-flex align-items-center"
                                                    onclick="return confirm('Anda yakin akan menghapus dokumen?')"
                                                    title="Kirim">
                                                    <i class="fas fa-ptrashe me-1" style="font-size: 12px;"></i> Send
                                                </button>
                                            </form>
                                            @endif
                                            @else
                                            <span class="text-danger">No corresponding request found</span>
                                            @endif
                                            @elseif ($incident_fix->status_request == 'Rejected')
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
                    <form id="requestForm" method="POST" action="{{ route('operator.incident.storeRequest') }}">
                        @csrf
                        <input type="hidden" id="sentincidentId" name="sent_incident_id">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<script>
    // Ambil CSRF token dari meta tag
    var csrfToken = '{{ csrf_token() }}';

    // Fungsi untuk menampilkan modal permintaan edit/hapus
    function showRequestModal(id) {
        $('#sentincidentId').val(id);
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
            url: "{{ route('operator.incident.approve', '') }}/" + id, // Menggunakan route Laravel
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
            url: "{{ route('operator.incident.reject', '') }}/" + id, // Menggunakan route Laravel
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
        window.location.href = "{{ url('operator/incident/edit') }}/" + id; // Menggunakan URL Laravel
    }

    // Fungsi untuk mengedit item yang telah dikirim
    function sentEditAction(id) {
        window.location.href = "{{ url('operator/incident/sent_edit') }}/" + id; // Menggunakan URL Laravel
    }
</script>
@endsection