<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Perfiles - TAP Terminal</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #f9d849; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; margin: 0; color: #111; }
        .subtitle { font-size: 12px; color: #666; margin: 5px 0 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #222; color: #fff; padding: 10px 8px; text-align: left; font-size: 11px; text-transform: uppercase; }
        td { padding: 10px 8px; border-bottom: 1px solid #eee; vertical-align: top; }
        .badge { 
            background-color: #f1f3f4; 
            color: #3c4043; 
            padding: 3px 6px; 
            border-radius: 4px; 
            font-size: 10px; 
            display: inline-block; 
            margin: 2px;
            border: 1px solid #dadce0;
            font-family: monospace;
        }
        .no-sections { color: #999; italic; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">TAP TERMINAL - SEGURIDAD Y ACCESOS</h1>
        <p class="subtitle">Catálogo de Perfiles de Usuario y Secciones Asignadas</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Código Perfil</th>
                <th style="width: 25%;">Nombre del Perfil</th>
                <th style="width: 45%;">Secciones / Permisos Permitidos</th>
                <th style="width: 15%;">Configurado El</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $profile)
            <tr>
                <td><strong>{{ $profile->code }}</strong></td>
                <td>{{ $profile->name }}</td>
                <td>
                    @if(!empty($profile->sections))
                        @foreach($profile->sections as $section)
                            <span class="badge">{{ $section }}</span>
                        @endforeach
                    @else
                        <span class="no-sections">Ninguna sección asignada</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($profile->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>