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
</form>
@endsection