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
                                <label for="plant">{{ __('Plant') }}</label>
                                <input class="form-control" type="text" id="plant" name="plant" value="{{ old('plant') }}" required>
                                @error('plant')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                                <label for="pr_category">{{ __('Kategori PR') }}</label>
                                <select class="form-control" id="pr_category" name="pr_category" required>
                                    <option value="" disabled selected>Pilih Kategori PR</option>
                                    <option value="Stock: PTD" {{ old('pr_category') == 'Stock: PTD' ? 'selected' : '' }}>Stock: PTD</option>
                                    <option value="Non Stock: PTD" {{ old('pr_category') == 'Non Stock: PTD' ? 'selected' : '' }}>Non Stock: PTD</option>
                                </select>
                                @error('pr_category')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="account_assignment">{{ __('Account Assignment') }}</label>
                                <input class="form-control" type="text" id="account_assignment" name="account_assignment" value="{{ old('account_assignment') }}" required>
                                @error('account_assignment')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="item_category">{{ __('Kategori Item') }}</label>
                                <input class="form-control" type="text" id="item_category" name="item_category" value="{{ old('item_category') }}" required>
                                @error('item_category')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                                <label for="material_code">{{ __('Kode Material') }}</label>
                                <select class="form-control" id="material_code" name="material_code" required>
                                    <option value="">Pilih Material</option>
                                    @foreach($materials as $material)
                                    <option value="{{ $material->material_code }}" {{ old('material') == $material->material_code ? 'selected' : '' }}>{{ $material->material_code }}-{{ $material->description }}</option>
                                    @endforeach
                                </select>
                                @error('material_code')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="short_text">{{ __('Deskripsi Singkat') }}</label>
                                <input class="form-control" type="text" id="short_text" name="short_text" value="{{ old('short_text') }}" required>
                                @error('short_text')
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
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit">{{ __('Satuan') }}</label>
                                <select class="form-control" id="unit" name="unit" required>
                                    <option value="">Pilih Satuan</option>
                                    @foreach($units as $unit)
                                    <option value="{{ $unit->unit }}" {{ old('unit') == $unit->unit ? 'selected' : '' }}>{{ $unit->unit }}</option>
                                    @endforeach
                                </select>
                                @error('unit')
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
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="io_assetcode">{{ __('IO / Assetcode') }}</label>
                                <select class="form-control" id="io_assetcode" name="io_assetcode" required>
                                    <option value="">Pilih Internal Order</option>
                                    @foreach($budgets as $budget)
                                    <option value="{{ $budget->internal_order }}" {{ old('io_assetcode') == $budget->internal_order ? 'selected' : '' }}>{{ $budget->internal_order }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gl_account">{{ __('GL Account') }}</label>
                                <select class="form-control" id="gl_account" name="gl_account" required>
                                    <option value="">Pilih GL Account</option>
                                    @foreach($gls as $gl)
                                    <option value="{{ $gl->gl_code }}" {{ old('gl_account') == $gl->gl_code ? 'selected' : '' }}>{{ $gl->gl_code }}-{{ $gl->gl_name }}</option>
                                    @endforeach
                                </select>
                                @error('gl_account')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cost_center">{{ __('Cost Center') }}</label>
                                <input class="form-control" type="text" id="cost_center" name="cost_center" value="{{ old('cost_center') }}">
                                @error('cost_center')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="matl_group">{{ __('Material Group') }}</label>
                                <input class="form-control" type="text" id="matl_group" name="matl_group" value="{{ old('matl_group') }}">
                                @error('matl_group')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchasing_group">{{ __('Group Pembelian') }}</label>
                                <select class="form-control" id="purchasing_group" name="purchasing_group" required>
                                    <option value="">Pilih Group Pembelian</option>
                                    @foreach($purs as $group)
                                    <option value="{{ $group->pur_grp }}" {{ old('purchasing_group') == $group->pur_grp ? 'selected' : '' }}>{{ $group->pur_grp }}</option>
                                    @endforeach
                                </select>
                                @error('purchasing_group')
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