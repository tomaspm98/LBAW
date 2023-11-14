@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}
    <label for="picture">Profile Picture</label>
    <input id="picture" type="file" name="picture" value="{{ old('picture') }}" required>
    @if ($errors->has('picture'))
      <span class="error">
          {{ $errors->first('picture') }}
      </span>
    @endif

    <label for="name">Username</label>
    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
    @if ($errors->has('username'))
      <span class="error">
          {{ $errors->first('username') }}
      </span>
    @endif

    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <label for="birthdate">Birthdate</label>
    <input id="birthdate" type="date" name="birthdate" value="{{ old('birthdate') }}" required>
    <script>

    </script>
    @if ($errors->has('birthdate'))
      <span class="error">
          {{ $errors->first('birthdate') }}
      </span>
    @endif

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" required>

    <button type="submit">
      Register
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
</form>
@endsection