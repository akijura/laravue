<?php

namespace App\Laravue\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectLevel
 *
 * @property string $name
 * @property string $description
 *  
 *
 * @method static ProjectLevel create(array $status)
 * @package App
 */
class ProjectLevel extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];
    protected $table = 'project_level';
}
