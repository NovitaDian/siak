@extends('layouts.user_type.auth')

@section('content')
<div class="row mt-4">
    <div class="col-md-4 mb-3">
        <form method="GET" action="{{ route('adminsystem.dashboard-budget') }}">
            <label for="year" class="form-label fw-bold">Filter Tahun:</label>
            <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Tahun</option>
                @foreach($years as $y)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6>Perbandingan Budget vs Penggunaan</h6>
            </div>
            <div class="card-body">
                <canvas id="budgetChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('dashboard')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($labels);
    const totalBudgets = @json($totalBudgets);
    const usages = @json($usages);

    const ctx = document.getElementById('budgetChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                    label: 'Usage',
                    data: usages,
                    backgroundColor: 'rgba(255, 152, 0, 0.6)'
                },
                {
                    label: 'Sisa Budget', 
                    data: totalBudgets.map((total, i) => total - usages[i]),
                    backgroundColor: 'rgba(76, 175, 80, 0.6)'
                }
            ]

        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: true 
                },
                y: {
                    beginAtZero: true,
                    stacked: true, 
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }



    });
</script>
@endpush