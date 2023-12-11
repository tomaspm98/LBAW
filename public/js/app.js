function encodeForAjax(data) {
  	if (data == null) return null;
  	return Object.keys(data).map(function(k){
    	return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  	}).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
  	let request = new XMLHttpRequest();

  	request.open(method, url, true);
  	request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  	request.addEventListener('load', handler);
  	request.send(encodeForAjax(data));
}

// Function for the notifications
// Runs when <a id="navbarDropdown" class="nav-link " role="button"> is clicked
// Uses the content from public function show($notification_id) in NotificationsController.php
// to create the notifications list

const navbarDropdown = document.getElementById('navbarDropdown');
const notificationDropdownContainer = document.getElementById('notificationDropdownContainer');

navbarDropdown.addEventListener('click', function () {
	updateUnreadNotifications();
	// Toggle visibility
	if (notificationDropdownContainer.hasChildNodes()) {
		notificationDropdownContainer.innerHTML = ''; // Remove content if exists
	} else {
		createNotificationDropdown(); // Create content if not exists
	}
}
);

function createNotificationDropdown() {
	const notificationDropdown = document.createElement('ul');
	notificationDropdown.className = 'dropdown-menu';
	notificationDropdown.id = 'dropdown-menu';

	if (authUserHasUnreadNotifications()) {
		const markAsReadButton = document.createElement('button');
		markAsReadButton.type = 'button';
		markAsReadButton.id = 'mark-as-read';
		markAsReadButton.innerText = 'Mark All as Read';
		notificationDropdown.appendChild(markAsReadButton);

		markAsReadButton.addEventListener('click', () => {
			sendAjaxRequest('post', '/mark-as-read', null, markAsReadHandler);
			
		});
	}
	const unreadNotificationsDiv = document.createElement('div');
	unreadNotificationsDiv.id = 'unread-notifications';
	createNotificationItems(window.unreadNotifications, unreadNotificationsDiv);
	notificationDropdown.appendChild(unreadNotificationsDiv);

	const readNotificationsDiv = document.createElement('div');
	readNotificationsDiv.id = 'read-notifications';
	createNotificationItems(window.readNotifications, readNotificationsDiv,5);
	notificationDropdown.appendChild(readNotificationsDiv);

	notificationDropdownContainer.appendChild(notificationDropdown);
	}

function authUserHasUnreadNotifications() {
  	return window.unreadNotifications.length > 0;
}

function authUserUnreadNotifications() {
	sendAjaxRequest('post', '/get-unread-notifications', null, updateUnreadNotificationsVar);
	return window.unreadNotifications;
}

function authUserReadNotifications() {
	sendAjaxRequest('post', '/get-read-notifications', null, updateReadNotificationsVar);
	return window.readNotifications;
}


function createNotificationItems(notifications, container, limit = Infinity) {
	try {
		const slicedNotifications = notifications.slice(0, limit);

		slicedNotifications.forEach(notification => {
			const listItem = document.createElement('div');
			listItem.className = 'notification-item';

			const link = document.createElement('a');

			// Set the class based on whether the notification is read or unread
			link.className = notification.notification_is_read ? 'notification-read' : 'notification-unread';

			link.innerText = notification.notification_content;

			// Add a click event to the notification item
			link.addEventListener('click', () => {
				markIndividualAsRead(notification.notification_id);
			});

			listItem.appendChild(link);
			container.appendChild(listItem);
		});
	} catch (error) {
		console.error('Error creating notification items:', error);
	}
}

function markIndividualAsRead(notificationId) {
  	sendAjaxRequest('post', `/mark-as-read-individual/${notificationId}`, null, markIndividualAsReadHandler);
}


function markAsReadHandler() {
	try {
		const response = JSON.parse(this.responseText);

		if (response.success) {
			updateUnreadNotifications();
			const unreadNotificationsContainer = document.getElementById('unread-notifications');
			if (unreadNotificationsContainer) {
				// Delete content of the parent of unreadNotificationsContainer
				unreadNotificationsContainer.parentNode.innerHTML = '';
			}
			const notificationDropdown = document.createElement('ul');
			const readNotificationsDiv = document.createElement('div');
			readNotificationsDiv.id = 'read-notifications';
			createNotificationItems(window.readNotifications, readNotificationsDiv,5);
			notificationDropdown.appendChild(readNotificationsDiv);

			notificationDropdownContainer.appendChild(notificationDropdown);
		} else {
			showError('An error occurred while marking notifications as read.');
		}
	} catch (error) {
		showError('An error occurred while processing the response.');
	}
}

function markIndividualAsReadHandler() {
	try {
		const response = JSON.parse(this.responseText);

		if (response.success) {
		updateUnreadNotifications();
		console.log('Notification marked as read successfully');
		} else {
		showError('An error occurred while marking the notification as read.');
		}
	} catch (error) {
		console.error('Error parsing JSON response:', error);
		showError('An error occurred while processing the response: ' + error.message);
	}
}

function updateUnreadNotifications() {
    const notificationCountBadge = document.getElementById('notification-count-badge');
    if (notificationCountBadge) {
		authUserReadNotifications();
        notificationCountBadge.textContent = authUserUnreadNotifications().length;
    }
    
}

function updateUnreadNotificationsVar(){
	try {
		const response = JSON.parse(this.responseText);

		window.unreadNotifications = response;
		
	} catch (error) {
	showError('An error occurred while processing the response: ' + error.message);
	}
}
function updateReadNotificationsVar(){
	try {
		const response = JSON.parse(this.responseText);

		window.readNotifications = response;
		
	} catch (error) {
	showError('An error occurred while processing the response: ' + error.message);
	}
}

function showError(errorMessage) {
  	console.error(errorMessage);
}
