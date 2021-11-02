<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationChannel extends Model
{
    protected $fillable = [
        'name',
        'api_link'
    ];
}
