@extends('layouts.user_type.operator')

@section('content')
<div class="container-fluid">
    <div class="title-board text-center fw-bold fs-4 mb-4">SAFETY PERFORMANCE BOARD</div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-3">
            <form action="{{ route('operator.dashboard-incident') }}" method="GET" class="mb-3">
                <label class="form-label fw-bold">Filter Tanggal</label>
                <input type="date" name="filter_date" value="{{ request('filter_date') }}" class="form-control form-control-sm mb-2">

                <label for="shift" class="form-label fw-bold">Shift</label>
                <select class="form-control form-control-sm mb-2" name="shift" required>
                    <option value="Shift 1" {{ request('shift') == 'Shift 1' ? 'selected' : '' }}>Shift 1</option>
                    <option value="Shift 2" {{ request('shift') == 'Shift 2' ? 'selected' : '' }}>Shift 2</option>
                    <option value="Shift 3" {{ request('shift') == 'Shift 3' ? 'selected' : '' }}>Shift 3</option>
                </select>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-50">Filter</button>
                    <a href="{{ route('operator.dashboard-incident') }}" class="btn btn-secondary btn-sm w-50">Reset</a>
                </div>
            </form>

            <form action="{{ route('operator.update-target-manhours') }}" method="POST">
                @csrf
                <label for="target_manhours" class="form-label fw-bold">Target Total Man Hours</label>
                <input
                    type="number"
                    name="target_manhours"
                    id="target_manhours"
                    class="form-control form-control-sm mb-2"
                    value="{{ old('target_manhours', $targetManHours) }}"
                    min="0"
                    disabled>
            </form>
        </div>

         <!-- Right Column -->
        <div class="col-md-9">
            <!-- Tanggal -->
            <div class="wrap-text d-flex justify-content-between">
                <h6 class="fw-bold"><strong>Tanggal: {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</strong></h6>
                <h6 class="text-end fw-bold"><strong>Shift: {{($shift)}}</strong></h6>
            </div>
            <!-- Section 1: Counting Days Without Accident -->
            <div class="mb-4">
                <div class="section-title">COUNTING DAYS WITHOUT ACCIDENT</div>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <strong>Total Working Days Since Last LTA</strong><br>
                            (Total Hari Kerja Sejak LTA Terakhir)
                        </td>
                        <td class="text-end fw-bold">{{ $daysSinceLastLTA }}</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Total Working Days Since Last WLTA</strong><br>
                            (Total Hari Kerja Sejak WLTA Terakhir)
                        </td>
                        <td class="text-end fw-bold">{{ $daysSinceLastWLTA }}</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Total Man Hours Since Last LTA</strong><br>
                            (Total Jam Kerja Sejak LTA Terakhir)
                        </td>
                        <td class="text-end fw-bold">{{ number_format($manHoursSinceLastLTA) }}</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Total Man Hours Since Last WLTA</strong><br>
                            (Total Jam Kerja Sejak WLTA Terakhir)
                        </td>
                        <td class="text-end fw-bold">{{ number_format($manHoursSinceLastWLTA) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Section 2: Data Since Beginning of the Year -->
            <div>
                <div class="section-title">DATA SINCE BEGINNING OF THE YEAR</div>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <strong>Amount of Lost Time Accident (LTA)</strong><br>
                            (Jumlah Kecelakaan Kerja yang Hilang)
                        </td>
                        <td class="text-end fw-bold">{{ $totalLTA }}</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Amount of Without Lost Time Accident (WLTA)</strong><br>
                            (Jumlah Kecelakaan Tanpa Kehilangan Waktu Kerja)
                        </td>
                        <td class="text-end fw-bold">{{ $totalWLTA }}</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Total Amount of Working Hours</strong><br>
                            (Jumlah Total Jam Kerja)
                        </td>
                        <td class="text-end fw-bold">{{ number_format($totalManHours) }}</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>The Last Day Accident (LTA)</strong><br>
                            (Hari Terakhir Terjadi LTA)
                        </td>
                        <td class="text-end fw-bold">
                            {{ $lastLTAIncidentDate ? \Carbon\Carbon::parse($lastLTAIncidentDate)->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .title-board {
        font-weight: bold;
        font-size: 24px;
        margin-top: 20px;
    }

    .section-title {
        background: #d9edf7;
        color: #31708f;
        text-align: center;
        padding: 10px;
        font-weight: bold;
        font-size: 18px;
    }

    .table-bordered td,
    .table-bordered th {
        vertical-align: middle;
        text-align: left;
        padding: 8px;
        border: 1px solid #ddd;
    }
</style>
@endsection