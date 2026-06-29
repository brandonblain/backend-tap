<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Recupera la colección completa de productos desde MongoDB Atlas.
     */
    public function collection()
    {
        return Product::orderBy('created_at', 'desc')->get();
    }

    /**
     * Define los encabezados de la tabla para Excel.
     */
    public function headings(): array
    {
        return [
            'Código de producto',
            'Nombre del producto',
            'Marca / Fabricante',
            'Precio',
            'Fecha de creación'
        ];
    }

    public function map($product): array
    {
        return [
            $product->codigo,
            $product->nombre,
            $product->marca,
            '$' . number_format($product->precio, 2),
            Carbon::parse($product->created_at)->format('d/m/Y H:i') 
        ];
    }
}