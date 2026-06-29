<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Código de usuario',
            'Usuario (Email)',
            'Nombre Completo',
            'Teléfono de Contacto',
            'Perfiles Asignados (IDs)',
            'Fecha de Registro'
        ];
    }

    public function map($user): array
    {
        $perfilesTexto = !empty($user->profile_ids) ? implode(', ', $user->profile_ids) : 'Ninguno';

        return [
            $user->code,
            $user->username,
            $user->name,
            $user->phone ?? 'No registrado',
            $perfilesTexto,
            Carbon::parse($user->created_at)->format('d/m/Y H:i')
        ];
    }
}