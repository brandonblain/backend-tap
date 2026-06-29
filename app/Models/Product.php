<?php

namespace App\Models;

// 💡 Importante: Usamos el Eloquent de MongoDB, no el nativo de Laravel SQL
use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    /**
     * Define la conexión explícita a MongoDB Atlas.
     */
    protected $connection = 'mongodb';

    /**
     * Nombre de la colección dentro de MongoDB.
     */
    protected $collection = 'products';

    /**
     * Atributos asignables de forma masiva (Mass Assignment).
     */
    protected $fillable = [
        'codigo',
        'nombre',
        'marca',
        'precio',
    ];

    /**
     * Conversión de tipos nativos (Casts).
     * Asegura que el precio siempre se guarde y devuelva como flotante en Mongo.
     */
    protected $casts = [
        'precio' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
