<?php

namespace App\Laravue\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectComments
 *
 * @property integer $project_id
 * @property string $comment
 * @property integer $user_id
 *  
 *
 * @method static ProjectsComments create(array $comments)
 * @package App
 */
class ProjectComments extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'comment', 'user_id',
    ];
    protected $table = 'project_comments';

    
}
