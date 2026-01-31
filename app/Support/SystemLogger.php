<?php

namespace App\Support;

use App\Models\SystemLog;

class SystemLogger
{
    public static function log(
        string $action,
        string $message = null,
        string $level = 'info',
        array $context = [],
        ?int $userId = null
    ): SystemLog {
        return SystemLog::create([
            'level' => $level,
            'action' => $action,
            'message' => $message,
            'context' => $context,
            'user_id' => $userId,
        ]);
    }
}
