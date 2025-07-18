@extends('layouts.user_type.auth')

@section('content')

<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2"
    onclick="window.location.href='{{ route('adminsystem.inventory.index') }}'">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>Data Pemasukan</h6>
                <form action="{{ route('adminsystem.pemasukan.create') }}" method="GET" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary active mb-0 text-white" role="button" aria-pressed="true">
                        Tambah
                    </button>
                </form>
            </div>
            <div class="card-body px-4 pt-4 pb-4">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Barang</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Barang</th>
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
                                    <p class="text-xs font-weight-bold mb-0">{{ $pemasukan->barang->material_code }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pemasukan->barang->description }}</p>
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
                                    <p class="text-xs font-weight-bold mb-0">{{ $pemasukan->barang->unitId->unit }}</p>
                                </td>

                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pemasukan->keterangan }}</p>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('adminsystem.pemasukan.edit', $pemasukan->id) }}"
                                        class="btn btn-warning btn-xs mb-2"> <i class="fas fa-edit me-1" style="font-size: 12px;"></i> Edit

                                    </a>

                                    <!-- Tombol Send (Delete Action) -->
                                    <form action="{{ route('adminsystem.pemasukan.destroy', $pemasukan->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Anda yakin akan menghapus dokumen?')"
                                            title="Kirim"
                                            class="btn btn-danger btn-xs mb-2"> <i class="fas fa-trash me-1" style="font-size: 12px;"></i> Hapus
                                        </button>
                                    </form>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
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