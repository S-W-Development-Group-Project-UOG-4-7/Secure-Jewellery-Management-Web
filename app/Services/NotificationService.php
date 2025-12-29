<?php

namespace App\Services;

use App\Models\CustomNotification;

class NotificationService
{
    public function notifyUser(
        int $userId,
        string $title,
        ?string $body = null,
        ?string $type = 'system',
        ?string $url = null
    ): CustomNotification {
        return CustomNotification::create([
            'user_id' => $userId,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'url' => $url,
        ]);
    }
}
