<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Laravue\Models\Status;
use App\Laravue\Models\MainStatus;
use App\Laravue\Models\User;
use App\Laravue\Models\BasicStatus;
use App\Laravue\Models\ProjectLevel;
use App\Laravue\Models\ProjectReport;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->status_confirm != 1)
        {
            $status_type = ProjectReport::where([
                ['project_id','=',$this->id],
                ['status_confirm','=', 1]
            ])->latest()->first();
            $this->type_status = $status_type->type_status;
            $basic_status = Status::find($this->type_status);
            $this->basic_status =  $basic_status->basic_status;
        }
        $status_name = Status::find($this->type_status);
        $basic_status_name = BasicStatus::find($this->basic_status);
        $main_status_name = MainStatus::find($this->main_status_id);
        $user = User::find($this->author_id);
        $level = ProjectLevel::find($this->project_level);
        
        

        $from = strtotime($this->begin_date);
        $to = strtotime($this->end_date);
        $now =  time();
        $interval = $to - $from;
        $remained = $to - $now;
        $interval = round($interval / (60 * 60 * 24),1);
        $remained = round($remained / (60 * 60 * 24),1);
        
        
        if($remained < 0)
        {
            $remained = 'Finished';
        }
        if($remained > 0 && $remained < 1)
        {
            $remained = 1;
        }
        return [
            'basic_status' => $this->basic_status,
            'begin_date' => $this->begin_date,
            'description' => $this->description,
            'end_date' => $this->end_date,
            'interval' => $interval,
            'remained' => $remained,
            'id' => $this->id,
            'main_status_id' => $this->main_status_id,
            'name' => $this->name,
            'status' => $this->status,
            'type_status' => $this->type_status,
            'status_name' => $status_name->name,
            'main_status_name' => $main_status_name->name,
            'basic_status_name' => $basic_status_name->basic_status_name,
            'level_name' => $level->name,
            'level' => $level->id,
            'queue' => $status_name->queue,
            'percent' => $status_name->percent,
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'author' => $user->name,
        ];
    }
}
