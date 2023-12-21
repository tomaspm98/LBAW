@extends('layouts.app')

@section('content')
<div id="recover-password" class="align-items-center ">
    <form method="POST" action="{{ route('account-recovery') }}">
        {{ csrf_field() }}

        <p  class="explanation-recover">Forgot your account’s password? Enter your email address and we'll send you a recovery link.</p>
        <label for="email">E-mail</label>
        <input id="email" type="email" name="user_email" value="{{ old('email') }}" required autofocus placeholder="Your account's email" class="custom-placeholder">

        <button type="submit" class=" btn btn-primary">
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
</div>
@endsection