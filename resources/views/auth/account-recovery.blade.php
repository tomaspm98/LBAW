@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('account-recovery') }}">
    {{ csrf_field() }}

    <p>Forgot your account’s password? Enter your email address and we'll send you a recovery link.</p>
    <label for="email">E-mail</label>
    <input id="email" type="email" name="user_email" value="{{ old('email') }}" required autofocus>

    <button type="submit">
        Send recovery email
    </button>

    @error('email')
        <span class="error">
            {{ $message }}
        </span>
    @enderror

    @if(session('status') === 'success')
        <div class="success">Recovery email sent successfully!</div>
    @elseif(session('status') === 'error')
        <div class="error">{{ session('message') }}</div>
        
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
    @endif
</form>
@endsection