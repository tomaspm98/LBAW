@extends('layouts.app')

@section('content')

<div id="success-message"></div>

<div class="users-header">
    <h1 class="header-title">All Blocked users</h1>
</div>

@if($users->isEmpty())
    <p class="no-users">No users found.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Unblock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->username }}</td>
                    <td>                            
                        <form class="unblock-form" method="POST" action="{{ route('user.unblock', ['user_id' => $user->user_id]) }}">
                            @csrf
                            @method('POST')
                            <button type="submit">Unblock User</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.unblock-form');
        const successMessage = document.getElementById('success-message');

        forms.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        successMessage.textContent = `User unblocked.`;
                        successMessage.style.display = 'block';
                        setTimeout(() => {
                            successMessage.style.display = 'none';
                            location.reload(); // Reload the page after displaying the success message for a short delay
                        }, 3000); // Display the success message for 3 seconds
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });
</script>

@endsection
