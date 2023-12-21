@extends('layouts.app')

@section('content')
<div id="recover-password" class="align-items-center">
<form method="POST" action="{{ route('password-reset-post') }}">
    @csrf
    <h1 class="mb-3">Change Password</h1>
    <p class="explanation-recover">Enter your new password below.</p>
    <p class="explanation-recover">The password must be at least 8 characters.</p>
    <p class="explanation-recover">The password must include at least one lowercase letter, one uppercase letter, one number, and one special character</p>
    <p class="explanation-recover">Special characters:  .@$!%*#?&.</p>
    
    <div class="mt-4">
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
        <button type="submit" class="btn btn-primary">Change Password</button>
    </div>
</form>
</div>
@endsection