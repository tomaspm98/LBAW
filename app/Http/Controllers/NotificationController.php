<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Psy\Readline\Hoa\Console;
use Illuminate\Support\Facades\Auth;
use App\Events\NotificationsUpdated;
use Illuminate\Support\Facades\Log;
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
            Log::info('Notification marked as read.');
            event(new NotificationsUpdated(Auth::user()));
            Log::info('NotificationsUpdated event fired.');
            return response()->json(['success' => true, 'message'=> 'Notification marked as read.', 'notification_id' => $notification_id]);
        }

        return response()->json(['success' => false, 'message'=> 'Something went wrong.', 'notification_id' => $notification_id]);
    }
    // Mark all notifications as read
    public function markAllAsRead(Request $request)
    {
        $userId = Auth::id();

        $notifications = Notification::where('notification_user', $userId)
        ->where('notification_is_read', false)
        ->update(['notification_is_read' => true]);
        //Response
        event(new NotificationsUpdated(Auth::user()));
        return response()->json(['success' => true, 'message'=> 'Notification marked as read.']);
    }

    public function getUnreadNotifications(Request $request)
    {
        $readNotifications = Auth::user()->notifications
            ->where('notification_is_read', false)
            ->values()
            ->toArray();
        event(new NotificationsUpdated(Auth::user()));
        return response()->json($readNotifications);
    }

    
    public function getReadNotifications(Request $request)
    {
        $readNotifications = Auth::user()->notifications
            ->where('notification_is_read', true)
            ->values()
            ->toArray();
        
        event(new NotificationsUpdated(Auth::user()));
        
        return response()->json($readNotifications);
    }

    


    // Delete a notification
    public function delete($notification_id)
    {
        $notification = Notification::where('notification_id', $notification_id)->first();
        $notification->delete();
    }
    
}
