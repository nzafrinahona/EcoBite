<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function create(User $user, string $title, string $message, string $type = 'info'): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);
    }

    public function markAsRead(Notification $notification): void
    {
        $notification->update(['is_read' => true]);
    }

    public function markAllAsRead(User $user): void
    {
        $user->notifications()->unread()->update(['is_read' => true]);
    }

    public function getUnreadCount(User $user): int
    {
        return $user->notifications()->unread()->count();
    }
}
