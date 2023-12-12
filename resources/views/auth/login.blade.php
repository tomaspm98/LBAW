@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('login') }}" class="login-form">
    {{ csrf_field() }}

    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input id="email" type="email" name="user_email" value="{{ old('email') }}" required autofocus class="form-control input-field">
        @if ($errors->has('email'))
            <span class="error">{{ $errors->first('email') }}</span>
        @endif
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
    <a href="/account-recovery">Forgot password?</a>
        <input id="password" type="password" name="password" required class="form-control input-field">
        @if ($errors->has('password'))
            <span class="error">{{ $errors->first('password') }}</span>
        @endif
    </div>

    <div class="form-check mb-3 p-1">
        <input type="checkbox" id="rememberMe" name="remember" {{ old('remember') ? 'checked' : '' }} class="form-check-input">
        <label for="rememberMe" class="form-check-label">Remember me</label>
    </div>

    <button type="submit" class="btn btn-primary btn-login">Login</button>
    <a class="btn btn-outline-primary btn-register" href="{{ route('register') }}">Register</a>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif
</form>



@endsection