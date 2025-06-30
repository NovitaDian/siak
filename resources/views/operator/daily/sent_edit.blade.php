@extends('layouts.user_type.operator')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2" onclick="history.back()">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div>
    <div class="container-fluid">
        <h2 class="text-black font-weight-bolder text-center">DAILY ACTIVITIES & IMPROVEMENT REPORT</h2>
    </div>
    <div class="container-fluid py-4 px-0">
        <div class="card mx-auto w-100" style="max-width: 150%; ">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('EDIT DATA UMUM') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('operator.daily.sent_update', $daily_fix->id) }}" method="POST" role="form text-left">
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
                                <label for="tanggal_shift_kerja">{{ __('Tanggal Shift Kerja') }}</label>
                                <input class="form-control" type="date" id="tanggal_shift_kerja" name="tanggal_shift_kerja" value="{{ old('tanggal_shift_kerja', $daily_fix->tanggal_shift_kerja) }}" required>
                                @error('tanggal_shift_kerja')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shift_kerja">{{ __('Shift Kerja') }}</label>
                                <select class="form-control" id="shift_kerja" name="shift_kerja" required>
                                    <option value="" {{ old('shift_kerja', $daily_fix->shift_kerja) == '' ? 'selected' : '' }}>Pilih Shift</option>
                                    <option value="Shift 1" {{ old('shift_kerja', $daily_fix->shift_kerja) == 'Shift 1' ? 'selected' : '' }}>SHIFT I</option>
                                    <option value="Shift 2" {{ old('shift_kerja', $daily_fix->shift_kerja) == 'Shift 2' ? 'selected' : '' }}>SHIFT II</option>
                                    <option value="Shift 3" {{ old('shift_kerja', $daily_fix->shift_kerja) == 'Shift 3' ? 'selected' : '' }}>SHIFT III</option>
                                    <option value="Nonshift" {{ old('shift_kerja', $daily_fix->shift_kerja) == 'Nonshift' ? 'selected' : '' }}>NONSHIFT</option>
                                </select>
                                @error('shift_kerja')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="hse_inspector_id">{{ __('HSE Inspector') }}</label>
                            <select class="form-control" name="hse_inspector_id" id="hse_inspector_id" required>
                                <option value="">Pilih HSE Inspector</option>
                                @foreach($inspectors as $inspector)
                                <option value="{{ $inspector->id }}"
                                    {{ (old('hse_inspector_id', $daily_fix->hse_inspector_id) == $inspector->id) ? 'selected' : '' }}>
                                    {{ $inspector->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('hse_inspector_id')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="rincian_laporan">{{ __('Rincian Laporan') }}</label>
                                <textarea class="form-control" id="rincian_laporan" name="rincian_laporan" rows="4">{{ old('rincian_laporan', $daily_fix->rincian_laporan) }}</textarea>
                                @error('rincian_laporan')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('Update Report') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection