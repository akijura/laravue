<?php

namespace App\Laravue\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Laravue\Models\Status;

/**
 * Class Projects
 *
 * @property string $basic_status_name
 * @property string $basic_status_description
 * @property integer $author_id
 *  
 *
 * @method static BasicStatus create(array $status)
 * @package App
 */
class BasicStatus extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'basic_status_name', 'basic_status_description', 'author_id',
    ];
    protected $table = 'basic_status';

    public function status()
    {
        return $this->hasMany(Status::class);
    }
}
