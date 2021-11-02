<?php

namespace App\Laravue\Models;



use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

/**
 * Class Comments
 *
 * @property integer $project_id
 * @property string $comment
 * @property integer $user_id
 *
 * @method static Comments create(array $comments)
 * @package App
 */
class Comments extends Model
{
    use Notifiable, HasRoles, HasApiTokens;
   
    protected $fillable = [
        'project_id', 'comment', 'user_id',
    ];
    protected $table = 'project_comments';
}