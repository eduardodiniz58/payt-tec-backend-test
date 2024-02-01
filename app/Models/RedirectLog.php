<?php

// app/Models/RedirectLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedirectLog extends Model
{
    protected $fillable = [
        'redirect_id',
        'ip',
        'user_agent',
        'referer',
        'query_params',
        'accessed_at',
    ];

    // Adicione relações ou outros métodos conforme necessário
}
