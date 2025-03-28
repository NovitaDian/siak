@extends('layouts.user_type.auth')

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
                <button class="btn btn-primary" onclick="location.href='{{ route('adminsystem.pemasukan.index') }}'">Lihat Rincian</button>
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
                <button class="btn btn-primary" onclick="location.href='{{ route('adminsystem.pengeluaran.index') }}'">Lihat Rincian</button>
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
                    <table class="table align-items-center mb-0">
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

<!-- Row to Display Barang Images and Info -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card">
            <div class="nav-item d-flex flex-column align-items-start p-3">
                <form action="{{ route('adminsystem.barang.create') }}" method="GET" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary active mb-2 text-white" role="button" aria-pressed="true">
                        Tambah
                    </button>
                </form>


            </div>

            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Daftar Barang</h6>
            </div>

            <div class="card-body pt-4 p-3">
                <div class="row">
                    @foreach($barangs as $barang)
                    <div class="col-lg-2.5 col-md-3 col-sm-4 col-6 mb-3"> 
                        <div class="card">
                            <img src="{{ asset('storage/images/'.$barang->image) }}" alt="{{ $barang->description }}" class="card-img-top" style="height: 160px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title text-center" style="font-size: 0.8em; margin-bottom: 5px;">{{ $barang->description }}</h5>
                                <p class="card-text text-center" style="font-size: 0.8em; margin-bottom: 5px;">{{ $barang->material_code }}</p>
                                <p class="text-center" style="font-size: 0.8em; margin-bottom: 5px;">Qty: {{ $barang->quantity }} {{ $barang->unit }}</p>
                                <a href="{{ route('adminsystem.barang.detail', ['id' => $barang->id]) }}" class="btn btn-primary d-block" style="margin-top: 5px;">Lihat Detail</a>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>

                @if($barangs->isEmpty())
                <div class="text-center p-4">
                    <p class="text-secondary">Tidak ada barang untuk ditampilkan.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>


@endsection