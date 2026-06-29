<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Detallado de Usuarios - TAP Terminal</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #f9d849; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #333; color: #fff; padding: 6px; text-align: left; }
        td { padding: 6px; border-bottom: 1px solid #ddd; vertical-align: middle; }
        .avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid #ccc; }
        .badge { background-color: #f2f2f2; padding: 2px 5px; border-radius: 3px; font-size: 9px; margin-right: 2px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">TAP TERMINAL - GESTIÓN DE PERSONAL</h1>
        <p>Listado Maestro de Usuarios y Accesos</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Foto</th>
                <th>Código</th>
                <th>Usuario (Email)</th>
                <th>Nombre Completo</th>
                <th>Teléfono</th>
                <th>Perfiles (IDs)</th>
                <th>Alta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $user)
            <tr>
                <td>
                    @if($user->profile_picture)
                        <img src="{{ $user->profile_picture }}" class="avatar" alt="User">
                    @else
                        <span style="color: #999;">Sin foto</span>
                    @endif
                </td>
                <td><strong>{{ $user->code }}</strong></td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->phone ?? 'N/A' }}</td>
                <td>
                    @if(!empty($user->profile_ids))
                        @foreach($user->profile_ids as $profileId)
                            <span class="badge">{{ $profileId }}</span>
                        @endforeach
                    @else
                        <span style="color: #bcbcbc;">Ninguno</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>