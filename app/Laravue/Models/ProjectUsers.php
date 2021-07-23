<?php

namespace App\Laravue\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectUsers
 *
 * @property integer $project_id
 * @property integer $user_id
 *  
 *
 * @method static ProjectUsers create(array $users)
 * @package App
 */
class ProjectUsers extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'user_id',
    ];
    protected $table = 'project_users';

    
}
