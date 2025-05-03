@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">DETAIL BARANG</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Detail Barang') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <div class="row">
                    <!-- Image on the left -->
                    <div class="col-md-4 text-center">
                        <div class="form-group">
                            <label for="image">{{ __('Gambar Barang') }}</label>
                            <div>
                                @if($barangs->image)
                                <img src="{{ asset('storage/images/'.$barangs->image) }}" alt="{{ $barangs->description }}" class="card-img-top" style="object-fit: cover;"> @else
                                <p>No image available</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Details on the right -->
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="material_code">{{ __('Kode Barang') }}</label>
                            <input class="form-control" type="text" id="material_code" name="material_code" value="{{ $barangs->material_code }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="material_group">{{ __('Grup Material') }}</label>
                            <input class="form-control" type="text" id="material_group" name="material_group" value="{{ $barangs->material_group }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="description">{{ __('Deskripsi Barang') }}</label>
                            <input class="form-control" type="text" id="description" name="description" value="{{ $barangs->description }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="material_type">{{ __('Tipe Material') }}</label>
                            <input class="form-control" type="text" id="material_type" name="material_type" value="{{ $barangs->material_type }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="material_type">{{ __('Remark') }}</label>
                            <input class="form-control" type="text" id="material_type" name="material_type" value="{{ $barangs->remark }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="unit">{{ __('Unit') }}</label>
                            <input class="form-control" type="text" id="unit" name="unit" value="{{ $barangs->unit }}" disabled>
                        </div>
                    </div>
                </div>

                <!-- Buttons to Edit and Delete -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('adminsystem.barang.edit', $barangs->id) }}" class="btn bg-gradient-dark btn-md">{{ __('Edit Barang') }}</a>
                    <form action="{{ route('adminsystem.barang.destroy', $barangs->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-md">{{ __('Hapus Barang') }}</button>
                    </form>
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
    </div>
</div>




@endsection