<?php

namespace App\Laravue\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Projects
 *
 * @property string $name
 * @property string $description
 * @property string $begin_date
 * @property string $end_date
 * @property integer $main_status_id
 * @property integer $type_status
 * @property integer $author_id
 * @property integer $status
 *  
 *
 * @method static Projects create(array $project)
 * @package App
 */
class Projects extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'begin_date','end_date','main_status_id','type_status','author_id','status',
    ];
    protected $table = 'projects';

    
}
