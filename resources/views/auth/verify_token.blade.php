@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('token-verification-post') }}">
    {{ csrf_field() }}

    <p>Enter the token you received in your email.</p>
    <label for="token">Token</label>
    <input id="token" type="text" name="token" value="{{ old('token') }}" required autofocus>

    <button type="submit">
        Verify token
    </button>

    @error('token')
        <span class="error">
            {{ $message }}
        </span>
    @enderror
    
    
    @if(session('status') === 'success')
        <div class="success">Token verified successfully!</div>
    @elseif(session('status') === 'error')
        <div class="error">{{ session('message') }}</div>
        
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
    @elseif(session('status') )
        <div class="message">{{ session('status') }}</div>
        <div class="info">Please check your email for the token.</div>
    @endif
@endsection