@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">EDIT GL ACCOUNT</h2>
    </div>
    <div class="container-fluid py-4">
        <div class="card mx-auto w-100" style="max-width: 95%;">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('EDIT DATA GL ACCOUNT') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('adminsystem.budget.update', $budget->id) }}" method="POST" role="form text-left">
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
                                <label for="internal_order">{{ __('Internal Order') }}</label>
                                <input class="form-control" type="text" id="internal_order" name="internal_order" value="{{ old('internal_order', $budget->internal_order) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gl_code">{{ __('GL Code') }}</label>
                                <input class="form-control" type="text" id="gl_code" name="gl_code" value="{{ old('gl_code', $budget->gl_code) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gl_name">{{ __('GL Name') }}</label>
                                <input class="form-control" type="text" id="gl_name" name="gl_name" value="{{ old('gl_name', $budget->gl_name) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price_per_unit">{{ __('Price per Unit') }}</label>
                                <input class="form-control" type="number" id="price_per_unit" name="price_per_unit" value="{{ old('price_per_unit', $budget->price_per_unit) }}" step="0.01" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- January to December Fields (Adjusted to 2 columns per row) -->
                        @foreach(['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'] as $month)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="{{ $month }}_qty">{{ __(ucfirst($month) . ' Quantity') }}</label>
                                <input class="form-control" type="number" id="{{ $month }}_qty" name="{{ $month }}_qty" value="{{ old($month . '_qty', $budget->{$month . '_qty'}) }}" step="0.01" oninput="calculateTotal('{{ $month }}')">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="{{ $month }}_total">{{ __(ucfirst($month) . ' Total') }}</label>
                                <input class="form-control" type="text" id="{{ $month }}_total" name="{{ $month }}_total" value="{{ old($month . '_total', $budget->{$month . '_total'}) }}" readonly>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="setahun_qty">{{ __('Total Quantity Year') }}</label>
                                <input class="form-control" type="text" id="setahun_qty" name="setahun_qty" value="{{ old('setahun_qty', $budget->setahun_qty) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="setahun_total">{{ __('Total Price Year') }}</label>
                                <input class="form-control" type="text" id="setahun_total" name="setahun_total" value="{{ old('setahun_total', $budget->setahun_total) }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Update GL Account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function calculateTotal(month) {
        // Get price per unit
        var pricePerUnit = parseFloat(document.getElementById('price_per_unit').value);
        
        if (!pricePerUnit) {
            alert("Please enter the price per unit");
            return;
        }

        // Get quantity for the month
        var qty = parseFloat(document.getElementById(month + '_qty').value);
        
        // Calculate total for the month
        if (!isNaN(qty)) {
            var total = qty * pricePerUnit;
            document.getElementById(month + '_total').value = total.toFixed(2);
        }

        calculateYearTotal();
    }

    function calculateYearTotal() {
        var totalYearQty = 0;
        var totalYearPrice = 0;
        var months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];

        months.forEach(function(month) {
            var qty = parseFloat(document.getElementById(month + '_qty').value);
            var total = parseFloat(document.getElementById(month + '_total').value);
            
            if (!isNaN(qty)) totalYearQty += qty;
            if (!isNaN(total)) totalYearPrice += total;
        });

        document.getElementById('setahun_qty').value = totalYearQty.toFixed(2);
        document.getElementById('setahun_total').value = totalYearPrice.toFixed(2);
    }
</script>

@endsection
