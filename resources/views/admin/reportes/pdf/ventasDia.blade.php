<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ventas del Día</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2,
        h3 {
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f3f3f3;
        }
    </style>
</head>

<body>
    <h2>Ventas del Día: {{ $fecha }}</h2>
    <h3>Totales por tipo de pago:</h3>
    <ul>
        <li>Efectivo: Bs. {{ number_format($totales['efectivo'], 2) }}</li>
        <li>Tarjeta: Bs. {{ number_format($totales['tarjeta'], 2) }}</li>
        <li>QR: Bs. {{ number_format($totales['qr'], 2) }}</li>
    </ul>
    <h3>Total General: Bs. {{ number_format($totalGeneral, 2) }}</h3>

    <table>
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>ID Pedido</th>
                <th>Usuario</th>
                <th>Monto Total</th>
                <th>Método Pago</th>
                <th>Fecha Pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ventas as $venta)
                <tr>
                    <td>{{ $venta->idVenta }}</td>
                    <td>{{ $venta->idPedido }}</td>
                    <td>{{ $venta->pedido->usuario->nombre ?? '' }}</td>
                    <td>{{ number_format($venta->montoTotal, 2) }}</td>
                    <td>{{ ucfirst($venta->metodo_pago ?? '') }}</td>
                    <td>{{ $venta->fechaPago }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
