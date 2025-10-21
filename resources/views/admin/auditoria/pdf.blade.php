{{-- resources/views/auditoria/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe de Auditoría</title>
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

        th,
        td {
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

        .estado-exitoso {
            color: green;
            font-weight: bold;
        }

        .estado-fallido {
            color: red;
            font-weight: bold;
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
        <h1>Informe de Auditoría del Sistema</h1>
        <p>Registro de actividad de usuarios</p>
    </header>

    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Fecha / Hora</th>
                <th>Módulo</th>
                <th>IP</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ optional($log->causer)->nombre ?? 'N/A' }} {{ optional($log->causer)->apellido ?? '' }}</td>
                    <td>{{ optional($log->causer)->correo ?? 'N/A' }}</td>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $log->properties['modulo'] ?? 'N/A' }}</td>
                    <td>{{ $log->properties['ip_origen'] ?? 'N/A' }}</td>
                    <td
                        class="{{ ($log->properties['estado'] ?? '') === 'Exitoso' ? 'estado-exitoso' : 'estado-fallido' }}">
                        {{ $log->properties['estado'] ?? 'Desconocido' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">No hay registros disponibles</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <footer>
        Generado el {{ now()->format('d/m/Y H:i:s') }}
    </footer>
</body>

</html>
