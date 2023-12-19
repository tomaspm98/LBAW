<li class="nav-item dropdown">
    <a id="navbarDropdown" class="nav-link" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        <i class="fa fa-bell"></i>
        <span id="notification-count-badge" class="badge badge-light bg-success badge-xs">{{ Auth::user()->notifications->where('notification_is_read', false)->count() }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end w-30" id="notificationDropdownContainer">
        @if (auth()->user()->unreadNotifications)
        <li class="d-flex justify-content-end mx-1 my-2">
            <a href="" class="btn btn-success btn-sm">Mark All as Read</a>
        </li>
        @endif

        @foreach (auth()->user()->notifications as $notification)
        <a class="text" href="">
            @if ($notification->notification_is_read == false)
            <li class="unread-notification notification-box p-2 mb-2 border rounded bg-blue-200">
                <span style="text-decoration: none;">{{ $notification->notification_content }}</span><br>
                <span class="justify-content-end">{{ \Carbon\Carbon::parse($notification->notification_date)->format('M d, Y H:i:s') }}</span>
            </li>
            @elseif ($notification->notification_is_read == true)
            <li class="read-notification notification-box p-2 mb-2 border rounded bg-gray-200">
                <span style="text-decoration: none;">{{ $notification->notification_content }}</span><br>
                <span class="justify-content-end">{{ \Carbon\Carbon::parse($notification->notification_date)->format('M d, Y H:i:s') }}</span>
            </li>
            @endif
        </a>
        
        @endforeach
    </ul>
</li>
<script>
    var userId = {{ Auth::user()->user_id }};
    var channel = pusher.subscribe('notifications.'+userId);
    channel.bind('notifications', function(data) {
        alert(JSON.stringify(data));
    });
</script>
