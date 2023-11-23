@extends('layouts.app')

@section('content')
    <h1>All Users</h1>

    <div id="success-message"></div>

    @if($users->isEmpty())
        <p>No users found.</p>
    @else
        <table id="users-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Tags</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr id="user_{{ $user->user_id }}">
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->user_email }}</td>
                        <td>
                            <form class="add-moderator-form" method="POST" action="{{ route('moderator.add',  $user->user_id) }}">
                                @csrf
                                <input type="hidden" name="username" value="{{ $user->username }}">
                                <select name="tag_id">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->tag_id }}">{{ $tag->tag_name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit">Add Moderator</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Script for adding moderator and updating table -->
    <script>
        function removeUser(userId) {
            const userRow = document.getElementById('user_' + userId);
            userRow.remove();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.add-moderator-form');
            const successMessage = document.getElementById('success-message');

            forms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const formData = new FormData(this);
                    const userId = this.getAttribute('action').split('/').pop();
                    const username = formData.get('username'); // Get username from form data

                    fetch(this.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (response.ok) {
                            successMessage.textContent = `${username} added as a moderator.`;
                            successMessage.style.display = 'block';
                            removeUser(userId);
                            setTimeout(() => {
                                successMessage.style.display = 'none';
                            }, 3000);
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
