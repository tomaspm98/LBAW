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
    //console.log(response);
    displayMessage(response);
}
function markIndividualAsReadHandler() {
    const response = JSON.parse(this.responseText);
    //console.log(response);
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
// Function to update unread count in the UI
function updateUnreadCount(count) {
    // Assuming you have an element with ID 'unread-count' to display the count
    document.getElementById('unread-count').innerText = count;
}

// Function to create a new notification element
function createNotification(notificationId, notificationContent, notificationDate, notificationIsRead, notificationType) {
    // Create a new <a> element with the necessary classes and attributes
    const notificationElement = document.createElement('a');
    notificationElement.id = `notification-${notificationId}`;
    notificationElement.classList.add('notification-box', notificationIsRead ? 'read' : 'unread');

    // Create inner elements for content and date
    const contentElement = document.createElement('div');
    contentElement.classList.add('notification-content');
    contentElement.innerText = notificationContent;

    const dateElement = document.createElement('div');
    dateElement.classList.add('notification-date');
    const formattedDate = new Date(notificationDate).toLocaleString();
    dateElement.innerText = formattedDate;

    // Append content and date to the notification element
    notificationElement.appendChild(contentElement);
    notificationElement.appendChild(dateElement);

    // Append the notification element to the notifications container
    const notificationsContainer = document.getElementById('notifications-container');
    notificationsContainer.appendChild(notificationElement);
}


// Pusher code

channel.bind('notifications.updated', function(data) {
    //alert(JSON.stringify(data));

    //Actual Method
    //Update unread count
    updateUnreadCount(data.notifications.length);

    //Create new notifications not already displayed by if not displayed
    data.notifications.forEach(function(notification) {
        var notificationId = notification.notification_id;
        var notificationContent = notification.notification_content;
        var notificationDate = notification.notification_date;
        var notificationIsRead = notification.notification_is_read;
        var notificationType = notification.notification_type;

        //Check if notification is already displayed
        if($('#notification-'+notificationId).length == 0) {
            createNotification(notificationId, notificationContent, notificationDate, notificationIsRead, notificationType);
        }
    });
    
});

/*channel.bind('pusher:subscription_succeeded', function() {
    console.log('Subscription to notifications channel succeeded.');
});*/
    
channel.bind('pusher:error', function(error) {
    console.error('Pusher Error:', error);
});