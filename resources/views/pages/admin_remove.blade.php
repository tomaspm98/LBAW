@extends('layouts.app')

@section('content')
    <h1>All Moderators:</h1>
    @if($members->isEmpty())
        <p>No moderators found.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>tag</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    <tr>
                        <td>{{ $member->username }}</td>
                        <td>{{ $member->user_email }}</td>
                        <td>  </td>
                       
                        <!-- Other columns if needed -->
                        <td>
                            <form method="POST" action="{{ route('moderator.remove', ['userId' => $member->user_id]) }}">
                                @csrf
                                @method('DELETE') <!-- Use DELETE method if removing -->
                                <button type="submit">Remove Moderator</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
