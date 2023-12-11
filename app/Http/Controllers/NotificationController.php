<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Psy\Readline\Hoa\Console;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    
    // Show all unread notifications of a user
    public function showUnread()
    {
        $notifications = Notification::where('user_id', auth()->user()->id)
            ->where('notification_is_read', false)
            ->get();
        return $notifications;
    }
    // Show all read notifications of a user
    public function showRead()
    {
        $notifications = Notification::where('user_id', auth()->user()->id)
            ->where('notification_is_read', true)
            ->get();
        return $notifications;
    }
    // Show ten notifications of a user
    public function show()
    {
        $readNotifications = $this->showRead();
        $unreadNotifications = $this->showUnread();
        $notifications = [
            'read' => $readNotifications,
            'unread' => $unreadNotifications
        ];
        //Returns to be used by javascript
        return response()->json($notifications);
    }
    
    // Mark a notification as read
    public function markAsRead($notification_id)
    {
        $notification = Notification::where('notification_id', $notification_id)->first();

        if ($notification) {
            $notification->notification_is_read = true;
            $notification->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
    // Mark all notifications as read
    public function markAllAsRead(Request $request)
    {
        $userId = Auth::id();

        $notifications = Notification::where('notification_user', $userId)
        ->where('notification_is_read', false)
        ->update(['notification_is_read' => true]);
        //Response
        return response()->json(['success' => true]);

    }

    public function getUnreadNotifications(Request $request)
    {
        return response()->json(Auth::user()->notifications->where('notification_is_read', false));
    }

    public function getReadNotifications(Request $request)
    {
        return response()->json(Auth::user()->notifications->where('notification_is_read', true));
    }

    // Delete a notification
    public function delete($notification_id)
    {
        $notification = Notification::where('notification_id', $notification_id)->first();
        $notification->delete();
    }
    
}
