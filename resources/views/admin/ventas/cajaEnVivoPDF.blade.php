<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Caja en Vivo</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        .totales { background: #f0f0f0; font-weight: bold; }
    </style>
</head>
<body>
    <h2>📊 Resumen de Caja en Vivo</h2>
    <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    <p><strong>Usuario:</strong> {{ $caja['ciUsuario'] }}</p>

    <table>
        <tr>
            <th>Fondo Inicial</th>
            <td>{{ number_format($caja['fondo_inicial'], 2) }}</td>
        </tr>
        <tr>
            <th>Total Efectivo</th>
            <td>{{ number_format($totalEfectivo, 2) }}</td>
        </tr>
        <tr>
            <th>Total Tarjeta</th>
            <td>{{ number_format($totalTarjeta, 2) }}</td>
        </tr>
        <tr>
            <th>Total QR</th>
            <td>{{ number_format($totalQR, 2) }}</td>
        </tr>
        <tr class="totales">
            <th>Total en Caja</th>
            <td>{{ number_format($totalEnCaja, 2) }}</td>
        </tr>
    </table>

    <p><strong>Observaciones:</strong> {{ $caja['observaciones'] ?? '---' }}</p>
</body>
</html>
