<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Laravue\Models\Status;
use App\Laravue\Models\MainStatus;
use App\Laravue\Models\User;
use App\Laravue\Models\BasicStatus;
use Illuminate\Support\Facades\Auth;
class ProjectReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $status_name = Status::find($this->type_status);
        $basic_status_name = BasicStatus::find($status_name->basic_status);
        $user = User::find($this->user_id);
        $currentUser = Auth::user();
        $current = $currentUser->roles[0]['name'];
        return [
            'id' => $this->id,
            'status_id' => $this->type_status,
            'project_id' =>$this->project_id,
            'status_name' => $status_name->name,
            'basic_status_name' => $basic_status_name->basic_status_name,
            'status_confirm' => $this->status_confirm,
            'created_at' => date('Y-m-d', strtotime($this->created_at)),
            'user_role' => $current,
            'author' => $user->name,
        ];
    }
}
