@extends('layouts.user_type.tamu')

@section('content')
<button type="button" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2"
    onclick="window.location.href='{{ route('tamu.dashboard') }}'">
    <img src="{{ asset('assets/img/logos/arrow-back.png') }}" alt="Back" style="width: 40px; height: 40px;">
</button>
<div class="row mt-4">
    <!-- Left Column: Budget Monitoring Table -->
    <div class="col">
        <div class="card h-100 p-3">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">HSE Budget Monitoring</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">GL Account</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Internal Order</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Year</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">BG Approve</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">USAGE</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">% Usage</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sisa</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($budget_fixs as $budget_fix)
                            <tr>
                                <!-- Data from gl_account table -->
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $budget_fix->gl_code }} - {{ $budget_fix->gl_name }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $budget_fix->internal_order ?? '-' }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $budget_fix->year }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $budget_fix->bg_approve }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $budget_fix->usage  }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $budget_fix->percentage_usage  }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $budget_fix->sisa  }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $budget_fix->kategori  }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>


                </div>
            </div>
        </div>
    </div>
    </d>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize data tables
            $('#dataTable').DataTable({
                pageLength: 5,
                searching: true,
                ordering: true,
                responsive: true
            });


        });
    </script>
    @endsection