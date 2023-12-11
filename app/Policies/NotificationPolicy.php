<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Notification;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    public function viewNotifications(Member $user, Notification $notification)
    {
        return $user->user_id === $notification->notification_user;
    }
    public function modifyReadStatus(Member $user, $notification_user)
    {
        // Check if the authenticated user has the authority to modify read status based on id
        return $user->user_id === $notification_user;
    }
}
