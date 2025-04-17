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
            background: #0074c1;
            color: white;
            text-align: center;
            padding: 5px;
            font-weight: bold;
        }

        .table-bordered td,
        .table-bordered th {
            vertical-align: middle;
            text-align: center;
        }

        .remark {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container-fluid">

        <div class="title-board">SAFETY PERFORMANCE BOARD</div>
        <form action="{{ route('adminsystem.dashboard-incident') }}" method="GET" class="row mb-4">
            <div class="col-md-3">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('adminsystem.dashboard-incident') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <div class="row">
            <div class="col-md-6">
                <div class="section-title">COUNTING DAYS WITHOUT ACCIDENT</div>
                <table class="table table-bordered">
                    <tr>
                        <td>Total Working Days Since Last LTA</td>
                        <td>{{ $daysSinceLastLTA }}</td>
                    </tr>
                    <tr>
                        <td>Total Working Days Since Last WLTA</td>
                        <td>{{ $daysSinceLastWLTA }}</td>
                    </tr>
                    <tr>
                        <td>Total Man Hours Since Last LTA</td>
                        <td>{{ number_format($manHoursSinceLastLTA) }}</td>
                    </tr>
                    <tr>
                        <td>Total Man Hours Since Last WLTA</td>
                        <td>{{ number_format($manHoursSinceLastWLTA) }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <div class="section-title">DATA SINCE BEGINNING OF THE YEAR</div>
                <table class="table table-bordered">
                    <tr>
                        <td>Amount of Lost Time Accident (LTA)</td>
                        <td>{{ $totalLTA }}</td>
                    </tr>
                    <tr>
                        <td>Amount of Without Lost Time Accident (WLTA)</td>
                        <td>{{ $totalWLTA }}</td>
                    </tr>
                    <tr>
                        <td>Total Amount of Working Hours</td>
                        <td>{{ number_format($totalManHours) }}</td>
                    </tr>
                    <tr>
                        <td>The last day accident (LTA)</td>
                        <td>{{ $lastLTAIncidentDate ? \Carbon\Carbon::parse($lastLTAIncidentDate)->format('l, d F Y') : '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="section-title">NUMBERS OF WORK FORCE</div>
                <table class="table table-bordered">
                    <tr>
                        <td>SHIFT I</td>
                        <td>{{ $shift1 }}</td>
                    </tr>
                    <tr>
                        <td>SHIFT II</td>
                        <td>{{ $shift2 }}</td>
                    </tr>
                    <tr>
                        <td>SHIFT III</td>
                        <td>{{ $shift3 }}</td>
                    </tr>
                    <tr>
                        <td><b>TOTAL</b></td>
                        <td><b>{{ $totalShift }}</b></td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <div class="section-title">REMARK</div>
                <table class="table table-bordered">
                    <tr>
                        <td class="remark">SAFE</td>
                        <td style="background-color:green;"></td>
                    </tr>
                    <tr>
                        <td class="remark">LTA</td>
                        <td style="background-color:red;"></td>
                    </tr>
                    <tr>
                        <td class="remark">ROAD ACCIDENT</td>
                        <td style="background-color:blue;"></td>
                    </tr>
                    <tr>
                        <td class="remark">FATALITY</td>
                        <td style="background-color:black;"></td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</body>


@endsection