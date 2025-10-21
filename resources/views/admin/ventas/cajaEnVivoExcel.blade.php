<table>
    <thead>
        <tr>
            <th colspan="6">📊 Reporte de Caja</th>
        </tr>
        <tr>
            <th colspan="6">Fecha: {{ now()->format('d/m/Y H:i') }}</th>
        </tr>
        <tr>
            <th>Fondo Inicial</th>
            <th>Efectivo</th>
            <th>Tarjeta</th>
            <th>QR</th>
            <th>Total en Caja</th>
            <th>Observaciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $caja['fondo_inicial'] }}</td>
            <td>{{ $totalEfectivo }}</td>
            <td>{{ $totalTarjeta }}</td>
            <td>{{ $totalQR }}</td>
            <td>{{ $totalEnCaja }}</td>
            <td>{{ $caja['observaciones'] ?? '-' }}</td>
        </tr>
    </tbody>
</table>

<br><br>

<table>
    <thead>
        <tr>
            <th>ID Venta</th>
            <th>Cliente</th>
            <th>Método de Pago</th>
            <th>Monto</th>
            <th>Fecha Pago</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ventas as $venta)
            <tr>
                <td>{{ $venta->idVenta }}</td>
                <td>{{ $venta->pedido->usuario->nombre ?? 'N/A' }}</td>
                <td>{{ $venta->metodo_pago }}</td>
                <td>{{ $venta->montoTotal }}</td>
                <td>{{ $venta->fechaPago }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
