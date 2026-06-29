<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Productos - TAP Terminal</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #f9d849; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; margin: 0; color: #111; }
        .subtitle { font-size: 12px; color: #666; margin: 5px 0 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #222; color: #fff; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; }
        td { padding: 9px 8px; border-bottom: 1px solid #eee; }
        .text-bold { font-weight: bold; color: #111; }
        .price { color: #2e7d32; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">TAP TERMINAL - CONTROL DE INVENTARIOS</h1>
        <p class="subtitle">Catálogo Oficial de Productos Registrados</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Código</th>
                <th style="width: 35%;">Nombre del Producto</th>
                <th style="width: 25%;">Marca / Fabricante</th>
                <th style="width: 10%;">Precio</th>
                <th style="width: 15%;">Fecha Alta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $product)
            <tr>
                <td class="text-bold">{{ $product->codigo }}</td>
                <td>{{ $product->nombre }}</td>
                <td>{{ $product->marca }}</td>
                <td class="price">${{ number_format($product->precio, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($product->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>