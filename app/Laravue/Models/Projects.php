<?php

namespace App\Laravue\Models;



use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

/**
 * Class ServerTypes
 *
 * @property string $name
 * @property string $description
 * @property string $begin_date
 *
 * @method static Projects create(array $projects)
 * @package App
 */
class Projects extends Model
{
    use Notifiable, HasRoles, HasApiTokens;
   
    protected $fillable = [
        'name', 'description', 'begin_date','end_date','main_status_id','type_status','author_id','status','basic_status',
    ];
}