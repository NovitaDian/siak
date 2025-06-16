@extends('layouts.user_type.operator')

@section('content')

<div>
    <div class="container-fluid">
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
                            <div class="form-group">
                                <label for="pr_date">{{ __('Tanggal PR') }}</label>
                                <input class="form-control" type="date" id="pr_date" name="pr_date" value="{{ old('pr_date', $pr->pr_date) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pr_no">{{ __('Nomor PR') }}</label>
                                <input class="form-control" type="text" id="pr_no" name="pr_no" value="{{ old('pr_no', $pr->pr_no) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_for">{{ __('Dibeli Untuk') }}</label>
                                <select class="form-control" id="purchase_for" name="purchase_for" required>
                                    <option value="Healt & Safety" {{ old('purchase_for', $pr->purchase_for) == 'Healt & Safety' ? 'selected' : '' }}>Healt & Safety</option>
                                    <option value="Environment" {{ old('purchase_for', $pr->purchase_for) == 'Environment' ? 'selected' : '' }}>Environment</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material">{{ __('Deskripsi Material/jasa') }}</label>
                                <input class="form-control" type="text" id="material" name="material" value="{{ old('material', $pr->material) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity">{{ __('Kuantitas') }}</label>
                                <input class="form-control" type="number" id="quantity" name="quantity" value="{{ old('quantity', $pr->quantity) }}" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit">{{ __('Satuan') }}</label>
                                <select class="form-control" id="unit" name="unit" required>
                                    @foreach($units as $unit)
                                    <option value="{{ $unit->unit }}" {{ old('unit', $pr->unit) == $unit->unit ? 'selected' : '' }}>{{ $unit->unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="valuation_price">{{ __('Harga Valuasi') }}</label>
                                <input class="form-control" type="number" id="valuation_price" name="valuation_price" value="{{ old('valuation_price', $pr->valuation_price) }}" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="io_assetcode">{{ __('IO / Assetcode') }}</label>
                                <select class="form-control" id="io_assetcode" name="io_assetcode">
                                    <option value="">Pilih Internal Order</option>
                                    @foreach($budgets as $budget)
                                    <option value="{{ $budget->internal_order }}" {{ old('io_assetcode', $pr->io_assetcode) == $budget->internal_order ? 'selected' : '' }}>{{ $budget->internal_order }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gl_code">{{ __('GL Account') }}</label>
                                <select class="form-control" id="gl_code" name="gl_code" required>
                                    @foreach($gls as $gl)
                                    <option value="{{ $gl->gl_code }}" {{ old('gl_code', $pr->gl_code) == $gl->gl_code ? 'selected' : '' }}>{{ $gl->gl_code }} - {{ $gl->gl_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Update PR') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
