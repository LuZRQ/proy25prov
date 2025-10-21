<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Stock General</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
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
    <h2>Stock General: {{ $fecha }}</h2>
    <table>
        <thead>
            <tr>
                <th>ID Producto</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Stock Actual</th>
                <th>Stock Inicial</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->idProducto }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria->nombreCategoria ?? '' }}</td>
                    <td>{{ $producto->stock }}</td>
                    <td>{{ $producto->stock_inicial }}</td>
                    <td>{{ $producto->getEstadoStockNombre() }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
