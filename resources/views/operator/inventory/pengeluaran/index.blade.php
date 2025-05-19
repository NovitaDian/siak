@extends('layouts.user_type.operator')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Data Pengeluaran</h6>
                <form action="{{ route('operator.pengeluaran.create') }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-primary text-white mb-0">
                        Tambah
                    </button>
                </form>
            </div>
            <br>
            <div class="col-12">
                      <div class="card-body pt-4 p-3">
                <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="dataTable">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Barang</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengeluarans as $pengeluaran)
                                    <tr>
                                        <td class="text-center">{{ $pengeluaran->barang_id }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d/m/Y') }}</td>
                                        <td class="text-center">{{ $pengeluaran->quantity }}</td>
                                        <td class="text-center">{{ $pengeluaran->unit }}</td>
                                        <td class="text-center">{{ $pengeluaran->keterangan }}</td>
                                        <td class="text-center">
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('adminsystem.pengeluaran.edit', $pengeluaran->id) }}"
                                                class="btn btn-xs btn-warning">
                                                <i class="fas fa-edit me-1" style="font-size:12px;"></i> Edit
                                            </a>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('adminsystem.pengeluaran.destroy', $pengeluaran->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-danger btn-xs"
                                                    onclick="return confirm('Anda yakin akan menghapus dokumen?')"
                                                    style="display:inline-flex; align-items:center; padding:4px 8px; background:linear-gradient(to right, #FF4C4C, #FF0000); color:white; border-radius:5px; font-weight:bold; font-size:10px;">
                                                    <i class="fas fa-trash-alt me-1" style="font-size:12px;"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if($pengeluarans->isEmpty())
                            <div class="text-center py-4">
                                <p class="text-secondary">Tidak ada data pengeluaran.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables & dependencies -->
<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            ordering: true,
            searching: true,
            info: true,
            paging: true,
            responsive: true
        });
    });
</script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@endsection