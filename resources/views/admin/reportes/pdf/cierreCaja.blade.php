<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cierre de Caja - {{ $mes }}/{{ $anio }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 8px;
        }
        th {
            background-color: #f0f0f0;
            text-align: center;
        }
        td {
            text-align: right;
        }
        .left {
            text-align: left;
        }
        .total-mes {
            font-weight: bold;
            background-color: #ddd;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Cierre de Caja - {{ $mes }}/{{ $anio }}</h2>

    <table>
        <thead>
            <tr>
                <th class="left">Semana</th>
                <th>Efectivo (Incl. Fondo Inicial)</th>
                <th>Tarjeta</th>
                <th>QR</th>
                <th>Total Semana</th>
            </tr>
        </thead>
        <tbody>
            @foreach($semanas as $index => $semana)
                <tr>
                    <td class="left">
                        Semana {{ $index + 1 }}<br>
                        ({{ $semana['inicio']->format('d/m') }} - {{ $semana['fin']->format('d/m') }})
                    </td>
                    <td>{{ number_format($semana['efectivo'], 2, '.', ',') }}</td>
                    <td>{{ number_format($semana['tarjeta'], 2, '.', ',') }}</td>
                    <td>{{ number_format($semana['qr'], 2, '.', ',') }}</td>
                    <td>{{ number_format($semana['total'], 2, '.', ',') }}</td>
                </tr>
            @endforeach
            <tr class="total-mes">
                <td class="left">TOTAL MES</td>
                <td>{{ number_format($totalMes['efectivo'], 2, '.', ',') }}</td>
                <td>{{ number_format($totalMes['tarjeta'], 2, '.', ',') }}</td>
                <td>{{ number_format($totalMes['qr'], 2, '.', ',') }}</td>
                <td>{{ number_format($totalMes['general'], 2, '.', ',') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
