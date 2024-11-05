<!-- resources/views/reporte.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte TPP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 20px;
        }
        .container {
            width: 100%;
            border: 1px solid #ddd;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 1.5em;
        }
        .section {
            margin-top: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .data-table, .data-table th, .data-table td {
            width: 100%;
            border: 1px solid #ddd;
            border-collapse: collapse;
            text-align: left;
            padding: 8px;
        }
        .data-table th {
            background-color: #f5f5f5;
        }
        .footer {
            font-size: 0.8em;
            color: #777;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>SISIN - Transferencias Público Privadas</h1>
        <p>Dictamen de Inicio de Etapa Proyectos TPP</p>
        <p>Ministerio de Desarrollo Rural y Tierras</p>
    </div>

    <div class="section">
        <div class="section-title">I. Información General del Proyecto</div>
        <p><strong>Código TPP:</strong> {{ $datos['codigo_tpp'] }}</p>
        <p><strong>Nombre del Proyecto:</strong> {{ $datos['nombre_proyecto'] }}</p>
        <p><strong>Denominación (Nombre Original):</strong> {{ $datos['denominacion'] }}</p>
        <p><strong>Entidad Ejecutora:</strong> {{ $datos['entidad_ejecutora'] }}</p>
        <p><strong>Fecha Registro del Dictamen:</strong> {{ $datos['fecha_registro'] }}</p>
        <p><strong>Área de Influencia:</strong> {{ $datos['area_influencia'] }}</p>
        <p><strong>Duración del Proyecto:</strong> Inicio {{ $datos['duracion_inicio'] }} - Término {{ $datos['duracion_termino'] }}</p>
    </div>

    <div class="section">
        <div class="section-title">II. Problemática del Proyecto</div>
        <p><strong>Descripción del Problema:</strong> {{ $datos['problematicas'] }}</p>
        <p><strong>Descripción de la Solución:</strong> {{ $datos['solucion'] }}</p>
    </div>

    <div class="section">
        <div class="section-title">III. Objetivos del Proyecto</div>
        <p><strong>Objetivo General:</strong> {{ $datos['objetivo_general'] }}</p>
        <p><strong>Objetivo Específico:</strong> {{ $datos['objetivo_especifico'] }}</p>
    </div>

    <div class="section">
        <div class="section-title">IV. Costo Total de la Etapa (Expresado en Bolivianos)</div>
        <table class="data-table">
            <tr>
                <th>Componente</th>
                <th>Aporte Propio</th>
                <th>Contrapartida Nacional</th>
                <th>Préstamo Ext.</th>
                <th>Otro</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>Equipamiento (Maquinaria, Equipos y Vehículos)</td>
                <td>0</td>
                <td>0</td>
                <td>236,048</td>
                <td>0</td>
                <td>{{ number_format($datos['costo_total'], 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">V. Justificación y Respaldos</div>
        @foreach ($datos['justificaciones'] as $justificacion)
            <p>{{ $justificacion }} <strong>SI</strong></p>
        @endforeach
        <p><strong>Documento Legal:</strong> Decreto Supremo Nº 452, Fecha: {{ $datos['fecha_documento_legal'] }}</p>
        <p><strong>Reglamento para Transferencias Público Privadas:</strong> Resolución Ministerial Nº 525, Fecha: {{ $datos['fecha_resolucion'] }}</p>
    </div>

    <div class="footer">
        <p>Generado el {{ now()->format('d/m/Y') }} por el Sistema de Transferencias TPP</p>
    </div>
</div>

</body>
</html>