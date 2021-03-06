<?php

namespace App\Events;

use App\Laravue\Models\MainStatus;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Laravue\Models\ProjectUsers;
use MainTypeStatus;

class ProjectCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $project;
    protected $projectMembers;
    protected $projectParentStatus;


    public function __construct($project)
    {
        $this->project = $project;
        $this->projectUsers = \DB::table('project_users')
            ->selectRaw('user_id, (SELECT u.name FROM users u WHERE u.id = user_id) as name')
            ->where('project_id', $this->project->id)
            ->get();
        $this->projectParentStatus = MainStatus::select('name')->where('id', $this->project->main_status_id)->first();
    }

    public function message($userName) 
    {
        return "***š Hello, {$userName}!***\nā³ļø New project created and assigned to you\n\nāļø Name: {$this->project->name}\nšµ Main status: {$this->projectParentStatus->name}\nš Start date: {$this->project->begin_date}\nš End date: {$this->project->end_date}";
    }
}
