<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Daily Activities</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

    <h2>DAILY ACTIVITIES & IMPROVEMENT REPORT</h2>
    <div class="card-body px-4 pt-4 pb-4">
        <div class="table-responsive p-0">
            <table class="table align-items-center mb-0" id="dataTableSent">
                <thead>
                    <tr>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Shift Kerja</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shift Kerja</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HSE Inspector</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rincian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daily_fixs as $daily_fix)
                    <tr>
                        <td class="text-center text-xs">
                            <div class="d-flex justify-content-center align-items-center px-2 py-1">
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-xs">{{ \Carbon\Carbon::parse($daily_fix->tanggal_shift_kerja)->format('d/m/Y') }}</h6>
                                </div>
                            </div>
                        </td>
                        <td class="text-center text-xs">
                            <p class="text-xs font-weight-bold mb-0">{{ $daily_fix->shift_kerja }}</p>
                        </td>
                        <td class="text-center text-xs">
                            <p class="text-xs font-weight-bold mb-0">{{ $daily_fix->nama_hse_inspector }}</p>
                        </td>
                        <td class="text-center text-xs">
                            <p class="text-xs font-weight-bold mb-0">{{ $daily_fix->rincian_laporan }}</p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

</body>

</html>