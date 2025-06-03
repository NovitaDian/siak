@extends('layouts.user_type.operator')

@section('content')

<div class="row">
    <!-- First row with 2 columns for Penerimaan Barang and PR -->
    <div class="col-xl-6 col-sm-12 mb-xl-0 mb-4">
        <div class="card h-100 p-3">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Penerimaan Barang</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <!-- Konten Penerimaan Barang -->
                <p>Detail penerimaan barang</p>
                <button class="btn btn-primary btn-sm" onclick="location.href='{{ route('operator.pemasukan.index') }}'">Lihat Rincian</button>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-sm-12 mb-xl-0 mb-4">
        <div class="card h-100 p-3">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Pengeluaran Barang</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <!-- Konten PR -->
                <p>Detail Pengeluaran barang</p>
                <button class="btn btn-primary btn-sm" onclick="location.href='{{ route('operator.pengeluaran.index') }}'">Lihat Rincian</button>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Left Column: Transaction Table -->
    <div class="col-12">
        <div class="card h-100 p-3">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Transaksi</h6>
            </div>

            <div class="card-body pt-4 p-3">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="dataTransaksi">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Barang</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Barang</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Transaksi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Transaksi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Qty</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trans as $tran)
                            <tr>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $tran->material_code }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $tran->description }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm {{ $tran->type == 'Pengeluaran' ? 'bg-gradient-warning' : 'bg-gradient-success' }}">
                                        {{ $tran->type == 'Pengeluaran' ? 'Pengeluaran' : 'Pemasukan' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $tran->tanggal}}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $tran->quantity }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $tran->unit}}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $tran->keterangan }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($trans->isEmpty())
                    <div class="text-center p-4">
                        <p class="text-secondary">Tidak ada transaksi.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row mt-4">

    <div class="col-12">
        <div class="card h-100 p-3">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Barang</h6>
                <form action="{{ route('operator.barang.create') }}" method="GET" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm text-white"> Tambah
                    </button>
                </form>
            </div>


            <div class="card-body pt-4 p-3">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="dataBarang">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Gambar</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Barang</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Barang</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Qty</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangs as $barang)
                            <tr>
                                <td class="text-center">
                                    <div style="width:60px; height:60px; overflow:hidden; border-radius:4px; display:inline-block;">
                                        <img src="{{ asset('storage/images/'.$barang->image) }}"
                                            alt="{{ $barang->description }}"
                                            style="width:100%; height:100%; object-fit:cover;">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $barang->material_code }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $barang->description }}</p>
                                </td>



                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $barang->quantity }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $barang->unit}}</p>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('operator.barang.detail', ['id' => $barang->id]) }}" class="btn btn-primary d-block" style="margin-top: 5px;">Lihat Detail</a>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#dataTransaksi').DataTable({
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
        $('#dataBarang').DataTable({
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