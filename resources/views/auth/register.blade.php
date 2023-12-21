@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="register-form">
    {{ csrf_field() }}
    <h1>Register</h1>

    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus class="form-control">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">E-Mail Address</label>
        <input id="email" type="email" name="user_email" value="{{ old('user_email') }}" required class="form-control">
    </div>

    <div class="mb-3">
        <label for="birthdate" class="form-label">Birthdate</label>
        <input id="birthdate" type="date" name="user_birthdate" value="{{ old('user_birthdate') }}" required class="form-control">
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" type="password" name="password" required class="form-control">
    </div>

    <div class="mb-3">
        <label for="password-confirm" class="form-label">Confirm Password</label>
        <input id="password-confirm" type="password" name="password_confirmation" required class="form-control">
    </div>
    
    <div class="mb-3">
        <label for="picture" class="form-label">Profile Picture [Optional]</label>
        <input id="picture" type="file" name="picture" accept="image/png" class="form-control">
        <p class="accepted-formats">Accepted formats: png</p>
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

    <button type="submit" class="btn btn-primary">Register</button>
    <a class="btn btn-outline-primary" href="{{ route('login') }}">Login</a>
</form>

@endsection