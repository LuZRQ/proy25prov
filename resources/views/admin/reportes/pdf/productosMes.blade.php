<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Productos más vendidos del Mes</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 30px;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        header img {
            max-height: 80px;
            margin-bottom: 10px;
        }

        header h1 {
            font-size: 18px;
            margin: 0;
            font-weight: bold;
        }

        header p {
            font-size: 12px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #bbb;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f0f0f0;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        footer {
            position: fixed;
            bottom: 20px;
            width: 100%;
            text-align: right;
            font-size: 10px;
            color: #555;
            border-top: 1px solid #bbb;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('img/fondo3.png') }}" alt="Logo">
        <h1>Productos más vendidos del Mes</h1>
      <p>Mes: {{ now()->locale('es')->translatedFormat('F Y') }}</p>

    </header>

    <table>
        <thead>
            <tr>
                <th>ID Producto</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Cantidad Vendida</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            <tr>
                <td>{{ $producto->idProducto }}</td>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->categoria->nombreCategoria ?? '' }}</td>
                <td>{{ $producto->cantidad_vendida }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        Generado el {{ now()->format('d/m/Y H:i') }}
    </footer>
</body>
</html>
