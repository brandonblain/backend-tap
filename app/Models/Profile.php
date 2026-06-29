<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Profile extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'profiles';

    protected $fillable = [
        'name',
        'sections', // secciones que los usuarios pueden ver
    ];

    protected $casts = [
        'sections' => 'array',
    ];
}