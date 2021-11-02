<?php

namespace App\Laravue\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Laravue\Models\BasicStatus;

/**
 * Class Servers
 *
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property integer $main_status_id
 * 
 *
 * @method static Servers create(array $servers)
 * @package App
 */
class Status extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'status','main_status_id','queue','basic_status','percent'
    ];
    protected $table = 'types_status';

    public function basicStatus()
    {
        return $this->belongsTo(BasicStatus::class);
    }
    
}
