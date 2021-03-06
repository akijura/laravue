<?php

namespace App\Laravue\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectReport
 *
 * @property integer $project_id
 * @property integer $type_status
 * @property integer $user_id
 * @property integer $comment_id
 *  
 *
 * @method static ProjectReport create(array $reports)
 * @package App
 */
class ProjectReport extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'type_status', 'user_id','created_at','comment_id','status_confirm',
    ];
    protected $table = 'project_report';

    
}
