@extends('layouts.user_type.operator')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div>
    <div class="container-fluid ">
        <h2 class="text-black font-weight-bolder text-center">EDIT PURCHASE REQUEST</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%;">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('DATA UMUM') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('operator.pr.update', $pr->id) }}" method="POST" role="form text-left">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="budget_id" value="{{ $pr->budget_id }}">

                    @if($errors->any())
                    <div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white">{{ $errors->first() }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <label for="pr_date">{{ __('Tanggal PR') }}</label>
                            <input class="form-control" type="date" name="pr_date" id="pr_date" value="{{ old('pr_date', $pr->pr_date) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="pr_no">{{ __('Nomor PR') }}</label>
                            <input class="form-control" type="text" name="pr_no" id="pr_no" value="{{ old('pr_no', $pr->pr_no) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="purchase_for">{{ __('Dibeli Untuk') }}</label>
                            <select class="form-control" name="purchase_for" id="purchase_for" required>
                                <option value="">Pilih Opsi</option>
                                <option value="Healt & Safety" {{ old('purchase_for', $pr->purchase_for) == 'Healt & Safety' ? 'selected' : '' }}>Healt & Safety</option>
                                <option value="Environment" {{ old('purchase_for', $pr->purchase_for) == 'Environment' ? 'selected' : '' }}>Environment</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="material">{{ __('Deskripsi Material/jasa') }}</label>
                            <input class="form-control" type="text" name="material" id="material" value="{{ old('material', $pr->material) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="quantity">{{ __('Kuantitas') }}</label>
                            <input class="form-control" type="number" name="quantity" id="quantity" value="{{ old('quantity', $pr->quantity) }}" step="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label for="unit">{{ __('Satuan') }}</label>
                            <select class="form-control" id="unit_id" name="unit_id" required>
                                <option value="">Pilih Unit</option>
                                @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id', $pr->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->unit }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="valuation_price">{{ __('Harga Valuasi') }}</label>
                            <input class="form-control" type="number" name="valuation_price" id="valuation_price" value="{{ old('valuation_price', $pr->valuation_price) }}" step="0.01" required>
                        </div>

                        

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Update PR') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
