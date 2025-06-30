@extends('layouts.user_type.operator')

@section('content')

<div class="row">
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card d-flex flex-row">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="font-weight-bolder text-center">GL ACCOUNT</h5>
                <button class="btn btn-primary w-100" onclick="location.href='{{ route('operator.glaccount.index') }}'">GO</button>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card d-flex flex-row">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="font-weight-bolder text-center">MATERIAL GROUP</h5>
                <button class="btn btn-primary w-100" onclick="location.href='{{ route('operator.material_group.index') }}'">GO</button>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card d-flex flex-row">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="font-weight-bolder text-center">PERUSAHAAN</h5>
                <button class="btn btn-primary w-100" onclick="location.href='{{ route('operator.perusahaan.index') }}'">GO</button>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card d-flex flex-row">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="font-weight-bolder text-center">BAGIAN PERUSAHAAN</h5>
                <button class="btn btn-primary w-100" onclick="location.href='{{ route('operator.bagian.index') }}'">GO</button>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card d-flex flex-row">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="font-weight-bolder text-center">UNIT</h5>
                <button class="btn btn-primary w-100" onclick="location.href='{{ route('operator.unit.index') }}'">GO</button>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card d-flex flex-row">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="font-weight-bolder text-center">HEALTY AND SAFETY OFFICER</h5>
                <button class="btn btn-primary w-100" onclick="location.href='{{ route('operator.hse_inspector.index') }}'">GO</button>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-xl-4 col-sm-6 mb-4">
            <div class="card d-flex flex-row">
                <div class="card-body d-flex flex-column justify-content-between text-center">
                    <h5 class="font-weight-bolder">ALAT</h5>
                    <button class="btn btn-primary w-100" onclick="location.href='{{ route('operator.alat.index') }}'">GO</button>
                </div>
            </div>
        </div>
    </div>



    @endsection