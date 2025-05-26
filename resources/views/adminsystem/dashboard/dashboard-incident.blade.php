@extends('layouts.user_type.auth')

@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safety Performance Board</title>
    <style>
        .title-board {
            text-align: center;
            font-weight: bold;
            font-size: 24px;
            margin: 20px 0;
        }

        .section-title {
            background: #d9edf7;
            color: #31708f;
            text-align: center;
            padding: 10px;
            font-weight: bold;
            font-size: 18px;
        }

        .table-bordered {
            width: 100%;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .table-bordered td,
        .table-bordered th {
            vertical-align: middle;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        .target-manhours {
            background: #f2dede;
            padding: 10px;
            margin-top: 20px;
            font-weight: bold;
            text-align: center;
        }

        .box {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="title-board">SAFETY PERFORMANCE BOARD</div>

        <div class="row">
            <!-- Left Column: Filter & Target -->
            <div class="col-md-3">
                <form action="{{ route('adminsystem.dashboard-incident') }}" method="GET" class="mb-3 d-flex flex-column gap-2">
                    <label class="form-label fw-bold mb-1">Filter Tanggal</label>
                    <input type="date" name="filter_date" value="{{ request('filter_date') }}" class="form-control form-control-sm">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filter</button>
                        <a href="{{ route('adminsystem.dashboard-incident') }}" class="btn btn-secondary btn-sm flex-grow-1">Reset</a>
                    </div>
                </form>

                <form action="{{ route('adminsystem.update-target-manhours') }}" method="POST" class="mb-0 d-flex flex-column gap-2">
                    @csrf
                    <label for="target_manhours" class="form-label fw-bold mb-1">Target Total Man Hours</label>
                    <input
                        type="number"
                        name="target_manhours"
                        id="target_manhours"
                        class="form-control form-control-sm"
                        value="{{ old('target_manhours', $targetManHours) }}"
                        min="0"
                        required>
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </form>
            </div>

            <!-- Right Column: Data Tables -->
            <div class="col-md-9">
                <div class="row">
                    <!-- Counting Days Without Accident -->
                    <div class="col-md-12 mb-4">
                        <div class="section-title">COUNTING DAYS WITHOUT ACCIDENT</div>
                        <table class="table table-bordered">
                            <tr>
                                <td>Total Working Days Since Last LTA</td>
                                <td class="text-end">{{ $daysSinceLastLTA }}</td>
                            </tr>
                            <tr>
                                <td>Total Working Days Since Last WLTA</td>
                                <td class="text-end">{{ $daysSinceLastWLTA }}</td>
                            </tr>
                            <tr>
                                <td>Total Man Hours Since Last LTA</td>
                                <td class="text-end">{{ number_format($manHoursSinceLastLTA) }}</td>
                            </tr>
                            <tr>
                                <td>Total Man Hours Since Last WLTA</td>
                                <td class="text-end">{{ number_format($manHoursSinceLastWLTA) }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Data Since Beginning of the Year -->
                    <div class="col-md-12">
                        <div class="section-title">DATA SINCE BEGINNING OF THE YEAR</div>
                        <table class="table table-bordered">
                            <tr>
                                <td>Amount of Lost Time Accident (LTA)</td>
                                <td class="text-end">{{ $totalLTA }}</td>
                            </tr>
                            <tr>
                                <td>Amount of Without Lost Time Accident (WLTA)</td>
                                <td class="text-end">{{ $totalWLTA }}</td>
                            </tr>
                            <tr>
                                <td>Total Amount of Working Hours</td>
                                <td class="text-end">{{ number_format($totalManHours) }}</td>
                            </tr>
                            <tr>
                                <td>The last day accident (LTA)</td>
                                <td class="text-end">
                                    {{ $lastLTAIncidentDate ? \Carbon\Carbon::parse($lastLTAIncidentDate)->format('d/m/Y') : '-' }}
                                </td>

                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

@endsection