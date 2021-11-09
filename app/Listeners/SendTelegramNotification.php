<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Laravue\Models\NotificationChannel;
use App\Laravue\Models\NotificationCredential;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTelegramNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProjectCreated  $event
     * @return void
     */
    public function handle(ProjectCreated $event)
    {
        $api = NotificationChannel::find(1)->api_link;

        $ch = curl_init($api . '/sendMessage');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        foreach($event->projectUsers as $user) {
            $credential = NotificationCredential::select('identifier')->where(['user_id' => $user->user_id, 'channel_id' => 1])->get();
            if($credential->isEmpty()) {
                continue;
            }
            
            $params = [
                'chat_id' => $credential[0]->identifier,
                'text' => $event->message($user->name),
                'parse_mode' => 'Markdown',
            ];
            curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
            $result = curl_exec($ch);
        }
        curl_close($ch);
    }
}
