@extends('layouts.user_type.auth')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>

<div>
    <div class="container-fluid ">
        <h2 class="text-black font-weight-bolder text-center">CREATE PURCHASE REQUEST</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('DATA UMUM') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('adminsystem.pr.store') }}" method="POST" role="form text-left">
                    @csrf
                    @if($errors->any())
                    <div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">{{ $errors->first() }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    @if(session('success'))
                    <div class="m-3 alert alert-success alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif
                    <input type="hidden" name="budget_id" value="{{ request('budget_id') }}">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pr_date">{{ __('Tanggal PR') }}</label>
                                <input class="form-control" type="date" id="pr_date" name="pr_date" value="{{ old('pr_date') }}" required>
                                @error('pr_date')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pr_no">{{ __('Nomor PR') }}</label>
                                <input class="form-control" type="text" id="pr_no" name="pr_no" value="{{ old('pr_no') }}" required>
                                @error('pr_no')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_for">{{ __('Dibeli Untuk') }}</label>
                                <select class="form-control" id="purchase_for" name="purchase_for" required>
                                    <option value="" disabled selected>Pilih Opsi</option>
                                    <option value="Healt & Safety" {{ old('purchase_for') == 'Healt & Safety' ? 'selected' : '' }}>Healt & Safety</option>
                                    <option value="Environment" {{ old('purchase_for') == 'Environment' ? 'selected' : '' }}>Environment</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material">{{ __('Deskripsi Material/jasa') }}</label>
                                <input class="form-control" type="text" id="material" name="material" value="{{ old('material') }}" required>

                                @error('material')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity">{{ __('Kuantitas') }}</label>
                                <input class="form-control" type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" step="0.01" required>
                                @error('quantity')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit_id">{{ __('Unit') }}</label>
                                <select class="form-control" id="unit_id" name="unit_id" required>
                                    <option value="">Pilih Unit</option>
                                    @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->unit}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="valuation_price">{{ __('Harga Valuasi') }}</label>
                                <input class="form-control" type="number" id="valuation_price" name="valuation_price" value="{{ old('valuation_price') }}" step="0.01" required>
                                @error('valuation_price')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Simpan PR') }}</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection