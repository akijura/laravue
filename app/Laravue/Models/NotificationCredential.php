<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationCredential extends Model
{
    protected $fillable = [
        'user_id',
        'channel_id',
        'identifier',
    ];

    public function channel()
    {
        return $this->belongsTo(NotificationChannel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
