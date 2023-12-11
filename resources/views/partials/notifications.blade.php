<a id="navbarDropdown" class="nav-link " role="button">
    <i class="fa fa-bell"></i>
    <span id="notification-count-badge" class="badge badge-light bg-success badge-xs">{{Auth::user()->notifications->where('notification_is_read', false)->count() }}</span>
</a>
<div id="notificationDropdownContainer"></div>
<script>
    window.unreadNotifications = @json(auth()->user()->unreadNotifications);
    window.readNotifications = @json(auth()->user()->readNotifications);
</script>
