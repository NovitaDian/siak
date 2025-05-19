@extends('layouts.user_type.auth')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="container-fluid py-4">

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6 class="mb-0 text-center">SAFETY PERFORMANCE INDICATOR (SPI)</h6>
                        <form method="GET" action="{{ route('adminsystem.dashboard-spi') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <select name="year" class="form-select" onchange="this.form.submit()">
                                        @foreach (range(date('Y'), 2020) as $y)
                                        <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>

                    </div>

                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table class="table table-bordered text-center align-middle" id="dataTable">
                                <thead class="bg-secondary text-white">
                                    <tr>
                                        <th rowspan="2">Kategori</th>
                                        @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $bulan)
                                        <th>{{ $bulan }}</th>
                                        @endforeach
                                        <th>Average</th>
                                        <th>YTD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $labels = [
                                    'lta' => ['label' => 'No. of LTA', 'class' => 'table-danger'],
                                    'wlta' => ['label' => 'No. of WLTA', 'class' => 'table-warning'],
                                    'ltifr' => ['label' => 'LTIFR - Lost Time Incident Frequency Rate', 'class' => 'fw-bold'],
                                    'man_hours' => ['label' => 'Man Hours'],
                                    'near_miss' => ['label' => 'No. of Near Miss', 'class' => 'table-success'],
                                    'illness_sick' => ['label' => 'No. of Illness/Sick', 'class' => 'table-success'],
                                    'first_aid_case' => ['label' => 'No. of First Aid Case (FAC)', 'class' => 'table-warning'],
                                    'medical_treatment_case' => ['label' => 'No. of Medical Treatment Case (MTC)', 'class' => 'table-warning'],
                                    'restricted_work_case' => ['label' => 'No. of Restricted Work Case (RWC)', 'class' => 'table-warning'],
                                    'lost_workdays_case' => ['label' => 'No. of Lost Workdays Case (LWC)', 'class' => 'table-danger'],
                                    'permanent_partial_dissability' => ['label' => 'No. of Permanent Partial Disability (PPD)', 'class' => 'table-danger'],
                                    'permanent_total_dissability' => ['label' => 'No. of Permanent Total Disability (PTD)', 'class' => 'table-danger'],
                                    'fatality' => ['label' => 'No. of Fatality', 'class' => 'table-dark'],
                                    'fire_incident' => ['label' => 'No. of Fire Incident', 'class' => 'table-dark'],
                                    'road_incident' => ['label' => 'No. of Road Incident', 'class' => 'table-warning'],
                                    ];

                                    // Ambil semua bulan untuk iterasi
                                    $months = collect($data)->pluck('month');
                                    $monthCount = $months->count();

                                    // Mengelompokkan nilai per kategori
                                    $grouped = [];
                                    foreach ($data as $row) {
                                    foreach ($row as $key => $value) {
                                    if ($key === 'month') continue;
                                    $grouped[$key][] = $value;
                                    }
                                    }
                                    @endphp

                                    @foreach ($grouped as $key => $values)
                                    @php
                                    $label = $labels[$key]['label'] ?? $key;
                                    $class = $labels[$key]['class'] ?? '';
                                    $ytd = array_sum($values);
                                    $avg = is_numeric($values[0]) ? number_format($ytd / $monthCount, 2, ',', '.') : '';
                                    @endphp
                                    <tr class="{{ $class }}">
                                        <td class="text-start fw-bold">{{ $label }}</td>
                                        @foreach ($values as $v)
                                        <td>{{ is_numeric($v) ? (is_float($v) ? number_format($v, 2, ',', '.') : $v) : $v }}</td>
                                        @endforeach
                                        <td class="fw-bold">{{ $avg }}</td>
                                        <td class="fw-bold">{{ is_numeric($ytd) ? $ytd : '-' }}</td>
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

<!-- DataTables -->
<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            responsive: true
        });
    });
</script>

@endsection