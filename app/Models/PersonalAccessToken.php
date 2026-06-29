<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumToken;
use MongoDB\Laravel\Eloquent\DocumentModel;

class PersonalAccessToken extends SanctumToken
{
    use DocumentModel;

    // Conectamos el token a tu clúster de Atlas
    protected $connection = 'mongodb';
    protected $table = 'personal_access_tokens';
    
    // Mongo usa _id de tipo string en lugar de enteros autoincrementables
    protected $primaryKey = '_id';
    protected $keyType = 'string';
}