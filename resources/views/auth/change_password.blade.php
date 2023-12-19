@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('password-reset-post') }}">
    @csrf
    <div>
        <label for="password">New Password</label>
        <input id="password" type="password" name="password" required>
    </div>

    <div>
        <label for="password_confirmation">Confirm New Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>
    </div>

    @if ($errors->any())
        <div id="errorPopup" class="popup-message alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <div>
        <button type="submit">Change Password</button>
    </div>
</form>
@endsection