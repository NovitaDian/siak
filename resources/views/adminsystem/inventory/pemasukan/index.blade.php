@extends('layouts.user_type.auth')

@section('content')
<div class="nav-item d-flex align-self-end">
    <form action="{{ route('adminsystem.pemasukan.create') }}" method="GET" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">
            Tambah
        </button>
    </form>
</div>
<br>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Data Pemasukan</h6>
            </div>
            <div class="card-body px-4 pt-4 pb-4">
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
                            @foreach ($pemasukans as $pemasukan)
                            <tr>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pemasukan->barang_id }}</p>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ \Carbon\Carbon::parse($pemasukan->tanggal)->format('d/m/Y') }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pemasukan->quantity }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pemasukan->unit }}</p>
                                </td>

                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pemasukan->keterangan }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <!-- Tombol Edit -->
                                    <a href="javascript:;"
                                        id="editBtn"
                                        onclick="editAction();"
                                        style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #FFA500, #FF6347); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px; margin-right: 8px;">
                                        <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit

                                    </a>

                                    <!-- Tombol Send (Delete Action) -->
                                    <form action="{{ route('adminsystem.pemasukan.destroy', $pemasukan->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm"
                                            onclick="return confirm('Anda yakin akan menghapus dokumen?')"
                                            title="Hapus"
                                            style="display: inline-flex; align-items: center; padding: 4px 8px; background: linear-gradient(to right, #FF4C4C, #FF0000); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 10px;">
                                            <i style="margin-right: 4px; font-size: 12px;"></i> Hapus
                                        </button>
                                    </form>


                                    <!-- Tambahkan font-awesome untuk ikon -->
                                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                                </td>

                                <script>
                                    function editAction() {
                                        // Redirect to the edit form for the item
                                        window.location.href = "{{ route('adminsystem.pemasukan.edit', $pemasukan->id) }}";
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
<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<script>
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