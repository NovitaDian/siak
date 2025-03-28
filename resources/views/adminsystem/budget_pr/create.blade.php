@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid ">
        <h2 class="text-black font-weight-bolder text-center">CREATE PURCHASE REQUEST</h2>
    </div>
    <div class="container-fluid py-4">
        <div class="card mx-auto w-100" style="max-width: 95%;">
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

                    <!-- Other fields like pr_date, pr_no, etc. -->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gl_code">{{ __('GL Code') }}</label>
                                <select class="form-control" id="gl_code" name="gl_code" required>
                                    <option value="">Pilih GL Code</option>
                                    @foreach($gl_accounts as $gl_account)
                                    <option value="{{ $gl_account->gl_code }}" {{ old('gl_code') == $gl_account->gl_code ? 'selected' : '' }}>{{ $gl_account->gl_code }} - {{ $gl_account->description }}</option>
                                    @endforeach
                                </select>
                                @error('gl_code')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="internal_order">{{ __('Internal Order') }}</label>
                                <select class="form-control" id="internal_order" name="internal_order" required>
                                    <option value="">Pilih Internal Order</option>
                                    @foreach($gl_accounts as $gl_account)
                                    <option value="{{ $gl_account->internal_order }}" {{ old('internal_order') == $gl_account->internal_order ? 'selected' : '' }}>{{ $gl_account->internal_order }} - {{ $gl_account->description }}</option>
                                    @endforeach
                                </select>
                                @error('internal_order')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description">{{ __('Description') }}</label>
                                <select class="form-control" id="description" name="description" required>
                                    <option value="">Pilih Deskripsi</option>
                                    @foreach($gl_accounts as $gl_account)
                                    <option value="{{ $gl_account->description }}" {{ old('description') == $gl_account->description ? 'selected' : '' }}>{{ $gl_account->description }}</option>
                                    @endforeach
                                </select>
                                @error('description')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Usage field based on PR table -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usage">{{ __('Usage') }}</label>
                                <input class="form-control" type="number" id="usage" name="usage" value="{{ old('usage') }}" required>
                                @error('usage')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Plan field based on budget table -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="plan">{{ __('Plan') }}</label>
                                <input class="form-control" type="number" id="plan" name="plan" value="{{ old('plan') }}" required>
                                @error('plan')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Percentage usage calculation -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="percentage_usage">{{ __('Percentage Usage') }}</label>
                                <input class="form-control" type="number" id="percentage_usage" name="percentage_usage" value="{{ old('percentage_usage') }}" required>
                                @error('percentage_usage')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Year -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="year">{{ __('Year') }}</label>
                                <input class="form-control" type="number" id="year" name="year" value="{{ old('year') }}" required>
                                @error('year')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
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
