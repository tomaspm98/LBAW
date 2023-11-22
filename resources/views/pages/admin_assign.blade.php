@extends('layouts.app')

@section('content')
    <h1>All Users</h1>

    @if($users->isEmpty())
        <p>No users found.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Tags</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
    <tr>
        <td>{{ $user->user_id }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->user_email }}</td>
        <td>
            <form method="POST" action="{{ route('moderator.add',  $user->user_id) }}">
                @csrf
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
@endsection
