<div class="d-flex align-items-center justify-content-between">
    <a id="navbarDropdown" class="nav-link d-flex align-items-center" role="button" href="#">
        <i class="fa fa-bell mx-1"></i>
        <span id="notification-count-badge" class="badge badge-light bg-success badge-xs">{{ Auth::user()->notifications->where('notification_is_read', false)->count() }}</span>
    </a>
</div>

<div id="notificationDropdownContainer"></div>
<script>
    window.unreadNotifications = @json(auth()->user()->unreadNotifications);
    window.readNotifications = @json(auth()->user()->readNotifications);
</script>
