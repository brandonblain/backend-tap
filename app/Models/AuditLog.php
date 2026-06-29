<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // Asegúrate de usar el modelo de Mongo

class AuditLog extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'audit_logs';

    protected $fillable = [
        'user_id',
        'user_username',
        'module',
        'action',
        'target_id',
        'old_data',
        'new_data',
    ];
}
