<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Events\ProjectStatusChanged;
use App\Laravue\Models\NotificationChannel;
use App\Laravue\Models\NotificationCredential;

class SendTelegramNotificationSubscriber
{
    protected $api;

    public function __construct()
    {
        $this->api = NotificationChannel::find(1)->api_link;
    }

    public function handleProjectCreated(ProjectCreated $event)
    {
        foreach ($event->projectUsers as $user) {
            $credential = NotificationCredential::select('identifier')->where(['user_id' => $user->user_id, 'channel_id' => 1])->get();
            if ($credential->isEmpty()) {
                continue;
            }
            $this->sendMessage($credential[0]->identifier, $event->message($user->name));
        }
    }

    public function handleProjectStatusChanged(ProjectStatusChanged $event)
    {
        foreach ($event->projectUsers as $user) {
            $credential = NotificationCredential::select('identifier')->where(['user_id' => $user->user_id, 'channel_id' => 1])->get();
            if ($credential->isEmpty()) {
                continue;
            }
            $this->sendMessage($credential[0]->identifier, $event->message($user->name));
        }
    }

    protected function sendMessage($chat_id, $text)
    {
        $params = [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'Markdown',
        ];
        $curlHandle = curl_init($this->api . '/sendMessage');
        curl_setopt($curlHandle, CURLOPT_HEADER, false);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, ($params));
        $apiResponse = curl_exec($curlHandle);
        curl_close($curlHandle);
    }


    public function subscribe($events)
    {
        $events->listen(
            ProjectCreated::class,
            [SendTelegramNotificationSubscriber::class, 'handleProjectCreated'],
        );

        $events->listen(
            ProjectStatusChanged::class,
            [SendTelegramNotificationSubscriber::class, 'handleProjectStatusChanged']
        );
    }
}
