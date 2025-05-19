@extends('layouts.user_type.operator')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>DETAIL INSPEKSI</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Pemeriksaan</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hse Inspector</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tool_fixs as $index => $tool_fix)
                                    <tr>
                                        <td class="text-center text-xs">{{ $index + 1 }}</td>
                                        <td class="text-center text-xs">{{ \Carbon\Carbon::parse($tool_fix->tanggal_pemeriksaan)->format('d/m/Y') }}</td>
                                        <td class="text-center text-xs">{{ $tool_fix->hse_inspector }}</td>
                                        <td class="text-center text-xs">{{ $tool_fix->status_pemeriksaan }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
