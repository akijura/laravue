<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Laravue\Models\User;
use Illuminate\Support\Facades\DB;
use App\Laravue\Models\Status;

class CommentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $files = DB::select("select * from files
        where project_id = ? and comment_id = ? order by name",[$this->project_id,$this->id]);

        $type_status = DB::select("select type_status as type_status from project_report
        where project_id = ? and comment_id = ? and user_id = ? ",[$this->project_id,$this->id,$this->user_id]);
     //   $status_name = Status::find($type_status['type_status']);
        if($type_status != null)
        {
            foreach($type_status as $status)
            {
                $status_name = Status::find($status->type_status);
                break;
            }
        }
        else {
            $status_name = Status::find(1);
        }
        $user = User::find($this->user_id);
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'comment' => $this->comment,
            'user_name' => $user->name,
            'files' => $files,
            'status_name' => $status_name->name,
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
        ];
    }
}
