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
    sendAjaxRequest('post', `/mark-as-read-individual/${notificationId}`, null, markIndividualAsReadHandler);
}

function markAllAsReadHandler() {
    // Changes the color of all the notifications to grey by changing the class
    const notificationItems = document.querySelectorAll('[id^="notification-"]');
    notificationItems.forEach(function(item) {
        item.classList.remove("unread");
        item.classList.add("read");
    });
    const response = JSON.parse(this.responseText);
    console.log(response);
    displayMessage(response);
}
function markIndividualAsReadHandler() {
    const response = JSON.parse(this.responseText);
    console.log(response);
    // Changes the color of the notification to grey by changing the class
    const notificationId = response.notification_id;
    const notificationItem = document.getElementById(`notification-${notificationId}`);
    notificationItem.classList.remove("unread");
    notificationItem.classList.add("read");
    displayMessage(response);
}

// Display success or error message based on the response
function displayMessage(response) {
    const successMessage = document.getElementById('acceptance-message');
    const errorMessage = document.getElementById('error-message');

    if (response.success) {
        successMessage.textContent = response.message;
        successMessage.hidden = false;
        // Hide error message
        errorMessage.innerHTML = '';
        errorMessage.hidden = true;
    } else {
        errorMessage.textContent = response.message;
        errorMessage.hidden = false;
        // Hide success message
        successMessage.innerHTML = '';
        successMessage.hidden = true;
    }
}