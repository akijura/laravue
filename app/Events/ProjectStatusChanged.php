<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $author;
    protected $project;
    protected $status;
    protected $comment;
    public $projectUsers;
    protected $oldStatus;

    public function __construct($options)
    {
        $this->project = $options['project'];
        $this->status = $options['status'];
        $this->author = $options['author'];
        $this->comment = $options['comment'];
        $this->oldStatus = $options['old_status'];

        $this->projectUsers = \DB::table('project_users')
            ->selectRaw('user_id, (SELECT u.name FROM users u WHERE u.id = user_id) as name')
            ->where('project_id', $this->project->id)
            ->get();
    }

    public function message($userName)
    {
        return "***👋 Hello, {$userName}!***\n✳️ The status of the _{$this->project->name}_ project has changed by {$this->author}\n\n🔸 Old status: {$this->oldStatus->name}\n🔹 New status: {$this->status->name}\n🕐 Updated at: {$this->status->updated_at}\n💬 Comment: {$this->comment}";
    }
}
