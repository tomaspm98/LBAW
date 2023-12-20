<div class="custom-dropdown">
    <button class="dropdown-toggle" id="customDropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-bell"></i>
        <span id="unread-count" class="badge badge-light bg-success badge-xs">{{Auth::user()->unreadNotifications->count()}}</span>
    </button>
    <div class="dropdown-menu" aria-labelledby="customDropdown">
        <div class="header-container">
            <div class="header">Notifications</div>
            <a id="mark-all-as-read" class="btn btn-primary">Mark All as Read</a>
        </div>
        <div class="dropdown-divider"></div>
        @foreach (auth()->user()->notifications as $notification)
            <a id="notification-{{$notification->notification_id}}" class="notification-box {{ $notification->notification_is_read ? 'read' : 'unread' }}">
                <div class="notification-content">
                    <span>{{ $notification->notification_content }}</span>
                </div>
                <div class="notification-date">
                    <span>{{ \Carbon\Carbon::parse($notification->notification_date)->format('M d, Y H:i:s') }}</span>
                </div>
            </a>
        @endforeach
        <div id="acceptance-message" class="alert alert-success" hidden></div>
        <div id="error-message" class="popup-message alert alert-danger" hidden></div>
    </div>
</div>
<script>
    var userId = {{ Auth::user()->user_id }};
    var channel = pusher.subscribe('notifications.'+userId);
</script>
