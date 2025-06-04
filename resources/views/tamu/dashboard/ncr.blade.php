@extends('layouts.user_type.tamu')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="container-fluid py-4">
        {{-- Filter Tahun --}}
        <div class="row mb-3 align-items-center">
            <div class="col-md-4">
                <form method="GET" action="{{ route('tamu.dashboard-ncr') }}">
                    <label for="year" class="form-label fw-bold">Filter Tahun:</label>
                    <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                        <option value="" @selected(empty($year))>All</option>
                        @foreach($years as $y)
                        <option value="{{ $y }}" @selected($y==$year)>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        {{-- Chart NCR --}}
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6 class="text-center">Perbandingan Status NCR (Open vs Closed)</h6>
                    </div>
                    <div class="card-body d-flex flex-column flex-md-row justify-content-around align-items-center gap-4">
                        {{-- Open Count --}}
                        <div class="d-flex flex-column align-items-center">
                            <h6 class="text-danger mb-1">Open</h6>
                            <p class="fs-4 fw-bold">{{ $openCount }}</p>
                        </div>

                        {{-- Chart --}}
                        <div style="width: 220px; height: 220px;">
                            <canvas id="ncrChart"></canvas>
                        </div>

                        {{-- Closed Count --}}
                        <div class="d-flex flex-column align-items-center">
                            <h6 class="text-success mb-1">Closed</h6>
                            <p class="fs-4 fw-bold">{{ $closedCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Sent NCR --}}
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header pb-0">
                        <h6 class="fw-bold">Data NCR</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table class="table align-items-center mb-0" id="sentNcrTable">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs fw-bold opacity-7">Tanggal Shift Kerja</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs fw-bold opacity-7">Shift Kerja</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs fw-bold opacity-7">Status NCR</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs fw-bold opacity-7">HSE Inspector</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs fw-bold opacity-7">Tanggal Audit</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs fw-bold opacity-7">Nama Auditee</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs fw-bold opacity-7">Durasi NCR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ncr_fixs as $ncr_fix)
                                    <tr class="sent-ncr-row" data-ncr-id="{{ $ncr_fix->id }}">
                                        <td class="text-center text-xs">{{ \Carbon\Carbon::parse($ncr_fix->tanggal_shift_kerja)->format('d/m/Y') }}</td>
                                        <td class="text-center text-xs">{{ $ncr_fix->shift_kerja }}</td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="badge badge-sm {{ $ncr_fix->status_ncr == 'Closed' ? 'bg-gradient-warning' : 'bg-gradient-success' }}">
                                                {{ $ncr_fix->status_ncr }}
                                            </span>
                                        </td>
                                        <td class="text-center text-xs">{{ $ncr_fix->nama_hs_officer_1 }}</td>
                                        <td class="text-center text-xs">{{ \Carbon\Carbon::parse($ncr_fix->tanggal_audit)->format('d/m/Y') }}</td>
                                        <td class="text-center text-xs">{{ $ncr_fix->nama_auditee }}</td>
                                        <td class="text-center text-xs">{{ $ncr_fix->durasi_ncr }}</td>
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
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const openCount = {{ $openCount ?? 0 }};
        const closedCount = {{ $closedCount ?? 0 }};

        const canvas = document.getElementById('ncrChart');
        if (!canvas) {
            console.error("Element #ncrChart not found");
            return;
        }

        const ctx = canvas.getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Open', 'Closed'],
                datasets: [{
                    label: 'Jumlah NCR',
                    data: [openCount, closedCount],
                    backgroundColor: ['#f5365c', '#2dce89'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    });
</script>


</main>

@endsection