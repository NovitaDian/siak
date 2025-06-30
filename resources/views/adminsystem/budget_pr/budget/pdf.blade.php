<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Budget</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <h2>Data Budget</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode GL</th>
                <th>Nama GL</th>
                <th>Internal Order</th>
                <th>Total 1 Tahun</th>
                <th>Tahun</th>
                <th>Sisa Budget</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($budgets as $budget)
            <tr>
                <td class="text-center text-xs">{{ $loop->iteration }}</td>
                <td class="text-center">
                    <p class="text-xs font-weight-bold mb-0">{{ $budget->gl_code }}</p>
                </td>
                <td class="text-center">
                    <p class="text-xs font-weight-bold mb-0">{{ $budget->gl_name }}</p>
                </td>
                <td class="text-center">
                    <p class="text-xs font-weight-bold mb-0">{{ $budget->internal_order ?? '-' }}</p>
                </td>
                <td class="text-center">
                    <p class="text-xs font-weight-bold mb-0">
                        Rp {{ number_format($budget->setahun_total, 0, ',', '.') }}
                    </p>
                </td>
                <td class="text-center">
                    <p class="text-xs font-weight-bold mb-0">{{ $budget->year }}</p>
                </td>
                <td class="text-center">
                    <p class="text-xs font-weight-bold mb-0">
                        Rp {{ number_format($budget->sisa, 0, ',', '.') }}
                    </p>
                </td>
                <td class="text-center">
                    <p class="text-xs font-weight-bold mb-0">{{ $budget->percentage_usage }} %</p>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>