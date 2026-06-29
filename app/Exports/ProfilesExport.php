<?php

namespace App\Exports;

use App\Models\Profile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ProfilesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Profile::orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Código de perfil',
            'Nombre del Perfil',
            'Secciones / Permisos Asignados',
            'Fecha de Creación'
        ];
    }

    public function map($profile): array
    {
        $seccionesTexto = !empty($profile->sections) ? implode(', ', $profile->sections) : 'Ninguna sección asignada';

        return [
            $profile->code,
            $profile->name,
            $seccionesTexto,
            Carbon::parse($profile->created_at)->format('d/m/Y H:i')
        ];
    }
}