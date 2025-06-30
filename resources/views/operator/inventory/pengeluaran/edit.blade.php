@extends('layouts.user_type.operator')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div class="container-fluid py-4">
    <div class="card mx-auto w-100" style="max-width: 95%;">
        <div class="card-header pb-0 px-3">
            <h6 class="mb-0">{{ __('Edit Pengeluaran Barang') }}</h6>
        </div>
        <div class="card-body pt-4 p-3">
            <form action="{{ route('operator.pengeluaran.update', $pengeluaran->id) }}" method="POST" role="form text-left" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if($errors->any())
                <div class="mt-3 alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="alert-text text-white">{{ $errors->first() }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fa fa-close" aria-hidden="true"></i>
                    </button>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal">{{ __('Tanggal') }}</label>
                            <input class="form-control" type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $pengeluaran->tanggal) }}" required>
                            @error('tanggal')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="barang_id">{{ __('Barang') }}</label>
                            <select class="form-control" id="barang_id" name="barang_id" required>
                                <option value="">Pilih Barang</option>
                                @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" {{ old('barang_id', $pengeluaran->barang_id) == $barang->id ? 'selected' : '' }}>
                                    {{ $barang->description }}
                                </option>
                                @endforeach
                            </select>
                            @error('barang_id')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="quantity">{{ __('Quantity') }}</label>
                            <input class="form-control" type="number" id="quantity" name="quantity" value="{{ old('quantity', $pengeluaran->quantity) }}" required>
                            @error('quantity')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="keterangan">{{ __('Keterangan') }}</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
                            @error('keterangan')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Update Pengeluaran') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection