<?php

namespace App\Laravue\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Servers
 *
 * @property string $name
 * @property string $description
 * @property integer $status
 * 
 *
 * @method static Servers create(array $servers)
 * @package App
 */
class MainStatus extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'status'
    ];
    protected $table = 'main_type_status';

    
}
