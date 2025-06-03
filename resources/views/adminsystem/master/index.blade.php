@extends('layouts.user_type.auth')

@section('content')
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin: 10px;">
    {{ session('success') }}
</div>
@endif
<div class="row">
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card d-flex flex-row">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="font-weight-bolder text-center">GL ACCOUNT</h5>
                <button class="btn btn-primary w-100" onclick="location.href='{{ route('adminsystem.glaccount.index') }}'">GO</button>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card d-flex flex-row">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="font-weight-bolder text-center">MATERIAL GROUP</h5>
                <button class="btn btn-primary w-100" onclick="location.href='{{ route('adminsystem.material_group.index') }}'">GO</button>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card d-flex flex-row">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="font-weight-bolder text-center">PERUSAHAAN</h5>
                <button class="btn btn-primary w-100" onclick="location.href='{{ route('adminsystem.perusahaan.index') }}'">GO</button>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card d-flex flex-row">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="font-weight-bolder text-center">BAGIAN PERUSAHAAN</h5>
                <button class="btn btn-primary w-100" onclick="location.href='{{ route('adminsystem.bagian.index') }}'">GO</button>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card d-flex flex-row">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="font-weight-bolder text-center">UNIT</h5>
                <button class="btn btn-primary w-100" onclick="location.href='{{ route('adminsystem.unit.index') }}'">GO</button>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-4">
        <div class="card d-flex flex-row">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="font-weight-bolder text-center">HEALTY AND SAFETY OFFICER</h5>
                <button class="btn btn-primary w-100" onclick="location.href='{{ route('adminsystem.hse_inspector.index') }}'">GO</button>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-xl-4 col-sm-6 mb-4">
            <div class="card d-flex flex-row">
                <div class="card-body d-flex flex-column justify-content-between text-center">
                    <h5 class="font-weight-bolder">ALAT</h5>
                    <button class="btn btn-primary w-100" onclick="location.href='{{ route('adminsystem.alat.index') }}'">GO</button>
                </div>
            </div>
        </div>
    </div>



    @endsection