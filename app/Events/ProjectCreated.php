<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Laravue\Models\ProjectUsers;


class ProjectCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $project;
    protected $projectMembers;


    public function __construct($project)
    {
        $this->project = $project;
        $this->projectUsers = \DB::table('project_users')
            ->selectRaw('user_id, (SELECT u.name FROM users u WHERE u.id = user_id) as name')
            ->where('project_id', $this->project->id)
            ->get();
    }

    public function message($userName) 
    {
        return "***👋 Hello, {$userName}!***\n✳️ New project created and assigned to you\n\n✏️ Name: {$this->project->name}\n🗓 Start date: {$this->project->begin_date}\n🔚 End date: {$this->project->end_date}";
    }
}
