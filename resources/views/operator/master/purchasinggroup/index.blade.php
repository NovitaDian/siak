@extends('layouts.user_type.operator')

@section('content')
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin: 10px;">
    {{ session('success') }}
</div>
@endif

<div class="nav-item d-flex align-self-end">
</div>
<br>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Unit</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Purchasing Group</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purs as $pur)
                            <tr>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pur->pur_grp }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $pur->department }}</p>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($purs->isEmpty())
                    <div class="text-center p-4">
                        <p class="text-secondary">Tidak ada data Unit.</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        @endsection