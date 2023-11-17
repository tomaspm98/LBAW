@extends('layouts.app')

@section('content')
    <h1>All Moderators:</h1>
    @if($moderators->isEmpty())
        <p>No moderators found.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <!-- Add more columns if needed -->
                </tr>
            </thead>
            <tbody>
                @foreach($moderators as $moderator)
                    <tr>
                        <td>{{ $moderator->user_id }}</td>
                        <td>{{ $moderator->username }}</td>
                        <td>{{ $moderator->user_email }}</td>
                        <!-- Other columns if needed -->
                        <td>
                            <input type="checkbox" name="selected_users[]" value="{{ $moderator->user_id }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Remove button -->
        <form method="POST" action="{{ route('moderator.remove', ['userId' => $moderator->user_id]) }}">
            @csrf
            @method('DELETE') <!-- Use DELETE method if removing -->
            <button type="submit">Remove as Moderator</button>
        </form>
    @endif
@endsection
