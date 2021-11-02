<?php

namespace App\Laravue\Models;



use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

/**
 * Class Files
 *
 * @property integer $project_id
 * @property string $comment
 * @property integer $user_id
 *
 * @method static Files create(array $files)
 * @package App
 */
class Files extends Model
{
    use Notifiable, HasRoles, HasApiTokens;
   
    protected $fillable = [
        'name', 'project_id', 'comment_id','author',
    ];
    protected $table = 'files';
}