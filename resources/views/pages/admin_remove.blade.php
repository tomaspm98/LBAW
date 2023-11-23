@extends('layouts.app')

@section('content')
    <h1>All Moderators:</h1>

    <div id="success-message"></div>
    

    @if($moderators->isEmpty())
        <p>No moderators found.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Tag</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                @foreach($moderators as $moderator)
                <tr id="user_{{ $moderator->user_id }}">
                        <td>{{ $moderator->member->username }}</td>
                        <td>{{ $moderator->member->user_email }}</td>
                        <td> {{ $moderator->tag->tag_name }} </td>
                        <td>
                            <form class="remove-moderator-form" method="POST" action="{{ route('moderator.remove', ['userId' => $moderator->user_id]) }}">
                                @csrf
                                <input type="hidden" name="username" value="{{ $moderator->member->username }}">
                                @method('DELETE') <!-- Use DELETE method if removing -->
                                <button type="submit">Remove as Moderator</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <script>
        function removeUser(userId) {
            const userRow = document.getElementById('user_' + userId);
            userRow.remove();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.remove-moderator-form');
            const successMessage = document.getElementById('success-message');

            forms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const formData = new FormData(this);
                    const userId = this.getAttribute('action').split('/').pop();
                    const username = formData.get('username'); // Get username from form data
                    console.log(username);


                    fetch(this.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (response.ok) {
                            successMessage.textContent = `${username} moderator removed.`;
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
