// Functions for the notifications

document.addEventListener("DOMContentLoaded", function() {
    // Get the mark all as read button
    const markAllAsReadButton = document.getElementById('mark-all-as-read');

    // Get all unread notification items by class
    var unreadNotificationItems = document.querySelectorAll('.unread');

    // Add click function to mark all as read button
    markAllAsReadButton.addEventListener("click", function() {
        markAllAsRead();
    });

    // Add click function to each unread notification item
    unreadNotificationItems.forEach(function(item) {
        item.addEventListener("click", function() {
            // Extract the notification ID from the element's ID
            var notificationId = item.id.replace("notification-", "");
            markIndividualAsRead(notificationId);
        });
    });
});

function markAllAsRead(){
    sendAjaxRequest('post', '/mark-as-read', null, markAllAsReadHandler);
}

function markIndividualAsRead(notificationId) {
    sendAjaxRequest('post', `/mark-as-read-individual/${notificationId}`, null, markIndividualAsReadHandler(notificationId));
}

function markAllAsReadHandler() {
    // Changes the color of all the notifications to grey by changing the class
    const notificationItems = document.querySelectorAll('[id^="notification-"]');
    notificationItems.forEach(function(item) {
        item.classList.remove("notification-unread");
        item.classList.add("notification-read");
    });
}
function markIndividualAsReadHandler($notificationId) {
    // Changes the color of the notification to grey by changing the class
    const notificationItem = document.getElementById(`notification-${notificationId}`);
    notificationItem.classList.remove("notification-unread");
    notificationItem.classList.add("notification-read");
}