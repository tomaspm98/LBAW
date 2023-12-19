<div class="custom-dropdown">
    <button class="dropdown-toggle" id="customDropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-bell"></i>
        <span class="badge badge-light bg-success badge-xs">4</span>
    </button>
    <div class="dropdown-menu" aria-labelledby="customDropdown">
        <div class="dropdown-header">Notifications</div>
        <a id="mark-all-as-read" class="btn btn-success btn-sm">Mark All as Read</a>
        <div class="dropdown-divider"></div>
        <div id="success-message" class="alert alert-success" style="display: none;"></div>
        <div id="error-message" class="alert alert-danger" style="display: none;"></div>
        @foreach (auth()->user()->notifications as $notification)
            <a id="notification-{{$notification->notification_id}}" class="notification-box {{ $notification->notification_is_read ? 'read' : 'unread' }}">
                <span>{{ $notification->notification_content }}</span><br>
                <span>{{ \Carbon\Carbon::parse($notification->notification_date)->format('M d, Y H:i:s') }}</span>
            </a>
        @endforeach
    </div>
</div>
<script>
    var userId = {{ Auth::user()->user_id }};
    var channel = pusher.subscribe('notifications.'+userId);
    channel.bind('notifications', function(data) {
        alert(JSON.stringify(data));
    });
</script>
