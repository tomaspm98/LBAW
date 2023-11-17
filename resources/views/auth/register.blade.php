@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
  {{ csrf_field() }}
      

  <label for="name">Username</label>
  <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>

  <label for="email">E-Mail Address</label>
  <input id="email" type="email" name="user_email" value="{{ old('user_email') }}" required>

  <label for="birthdate">Birthdate</label>
  <input id="birthdate" type="date" name="user_birthdate" value="{{ old('user_birthdate') }}" required>

  <label for="password">Password</label>
  <input id="password" type="password" name="password" required>

  <label for="password-confirm">Confirm Password</label>
  <input id="password-confirm" type="password" name="password_confirmation" required>
    
  <label for="picture">Profile Picture</label>
  <input id="picture" type="file" name="picture" value="{{ old('picture') }}" accept="image/png, image/jpeg, image/svg+xml">
  <p>Acepted formats: png, jpeg, svg</p>

  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <ol> {{ $error }}</ol>
            @endforeach
        </ul>
    </div>
  @endif

  <button type="submit">
    Register
  </button>
  <a class="button button-outline" href="{{ route('login') }}">Login</a>
</form>
@endsection