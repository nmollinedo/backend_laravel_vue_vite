<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>Fecha: {{ $date }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Monto (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item['nombre'] }}</td>
                    <td>{{ $item['monto'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>