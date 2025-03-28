@extends('layouts.user_type.auth')

@section('content')

<div class="row">
    <!-- First row with 2 columns for Budget Plan and PR -->
    <div class="col-xl-6 col-sm-12 mb-xl-0 mb-4">
        <div class="card h-100 p-3">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">BUDGET PLAN</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <!-- Konten Budget Plan -->
                <p>Detail rencana anggaran di sini...</p>
                <button class="btn btn-primary" onclick="location.href='{{ route('adminsystem.budget.index') }}'">Lihat Rincian</button>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-sm-12 mb-xl-0 mb-4">
        <div class="card h-100 p-3">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">PURCHASE REQUEST</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <!-- Konten PR -->
                <p>Detail PR di sini...</p>
                <button class="btn btn-primary" onclick="location.href='{{ route('adminsystem.pr.index') }}'">Lihat Rincian</button>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Left Column: Budget Monitoring Table -->
    <div class="col-xl-8 col-sm-12 mb-xl-0 mb-4">
        <div class="card h-100 p-3">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">HSE Budget Monitoring</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Internal Order</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">GL Account</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">BG Approve</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">USAGE</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Plan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">% Usage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($budget_fixs as $budget_fix)
                            <tr>
                                <!-- Data from gl_account table -->
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $budget_fix->internal_order }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $budget_fix->gl_account }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $budget_fix->glAccount->description ?? 'No description' }}</p>
                                </td>

                                <!-- bg_approve field -->
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $budget_fix->bg_approve }}</p>
                                </td>

                                <!-- Total price from the total_price table -->
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $budget_fix->totalPrice->total_price ?? 'No price' }}</p>
                                </td>

                                <!-- Plan from the budget table (setahun_total) -->
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $budget_fix->budget->setahun_total ?? 'No plan' }}</p>
                                </td>

                                <!-- Percentage usage calculation -->
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{ $budget_fix->bg_approve > 0 ? ($budget_fix->usage / $budget_fix->bg_approve) * 100 : 0 }}%
                                    </p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>

                    @if($budget_fixs->isEmpty())
                    <div class="text-center p-4">
                        <p class="text-secondary">Tidak ada data GL Account.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Chart -->
    <div class="col-xl-4 col-sm-12 mb-xl-0 mb-4">
        <div class="card h-100 p-3">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Dana Terpakai Tahun Ini</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <canvas id="prChart" width="250" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('prChart').getContext('2d');
    const prChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Dana Terpakai', 'Dana Tersisa'],
            datasets: [{
                label: 'Dana Terpakai Tahun Ini',
                data: [{
                    {
                        $danaTerpakai
                    }
                }, {
                    {
                        $danaTersisa
                    }
                }], // Pass data dynamically from the controller
                backgroundColor: ['#FF6347', '#2D8B4D'], // Example colors
                borderColor: ['#FF6347', '#2D8B4D'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw.toLocaleString() + ' IDR';
                        }
                    }
                }
            }
        }
    });
</script>

<style>
    /* Optional style adjustments */
    .card {
        border-radius: 10px;
    }

    table {
        width: 100%;
        text-align: left;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
</style>

@endsection