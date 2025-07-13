@extends('layouts.user_type.auth')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div>
    <div class="container-fluid ">
        <h2 class="text-black font-weight-bolder text-center">EDIT BUDGET PLAN</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%;">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('EDIT DATA BUDGET PLAN') }}</h6>
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
                                <label for="gl_id">{{ __('GL Code') }}</label>
                                <select class="form-control" id="gl_id" name="gl_id" required>
                                    <option value="">Pilih GL Code</option>
                                    @foreach($gls as $gl)
                                    <option value="{{ $gl->id }}" {{ (old('gl_id', $budget->gl_id) == $gl->id) ? 'selected' : '' }}>
                                        {{ $gl->gl_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('gl_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                      
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="year">{{ __('Year') }}</label>
                                <input class="form-control" type="text" id="year" name="year" value="{{ old('year', $budget->year) }}" required>
                            </div>
                        </div>
                    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kategori">{{ __('Kategori') }}</label>
                                <select class="form-control" id="kategori" name="kategori" required>
                                    <option value="CAPEX" {{ (old('kategori', $budget->kategori) == 'CAPEX') ? 'selected' : '' }}>CAPEX</option>
                                    <option value="OPEX" {{ (old('kategori', $budget->kategori) == 'OPEX') ? 'selected' : '' }}>OPEX</option>
                                    <option value="Consumable" {{ (old('kategori', $budget->kategori) == 'Consumable') ? 'selected' : '' }}>Consumable</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="setahun_total">{{ __('Total Harga Setahun') }}</label>
                                <input class="form-control" type="text" id="setahun_total" name="setahun_total" value="{{ old('setahun_total', $budget->setahun_total) }}">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('adminsystem.budget.index') }}" class="btn btn-secondary me-2 mt-4 mb-4">Cancel</a>
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Update Budget Plan') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('gl_code').addEventListener('change', function() {
        var gl_code = this.value;

        if (gl_code) {
            var url = '{{ route("adminsystem.budget.getGlName", ":gl_code") }}';
            url = url.replace(':gl_code', gl_code);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.gl_name) {
                        document.getElementById('gl_name').value = data.gl_name;
                    }
                })
                .catch(error => {
                    console.error('Error fetching GL name:', error);
                });
        } else {
            document.getElementById('gl_name').value = '';
        }
    });
</script>

@endsection