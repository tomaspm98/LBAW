@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Profile</h1>
        <form action="{{ route('user.update', $member->user_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your new username"> 
            </div>

            <div class="form-group">
                <label for="user_email">Email</label>
                <input class="form-control" id="user_email" name="user_email" placeholder="Enter your new email"> 
            </div>

            <div class="form-group">
                <label for="user_password">Password</label>
                <input class="form-control" id="user_password" name="user_password" placeholder="Enter your new password"> 
            </div>
            


            <div class="form-group">
                <label for="user_password_confirmation">Confirm Password</label>
                <input class="form-control" id="user_password2" name="user_password_confirmation" placeholder="Confirm your new password"> 
            </div>


            <div class="form-group">
                <label for="user_birthdate">Birthdate</label>
                <input type="date" id="user_birthdate" name="user_birthdate" placeholder="Enter your birthdate"> 
            </div>

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <ol> {{ $error }}</ol>
                    @endforeach
                </ul>
            </div>
          @endif
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
        <form action="{{ route('user.delete', $member->user_id) }}" method="post">
            @csrf
            @method('delete')
        
            <button type="submit" onclick="return confirm('Are you sure you want to delete your profile?')">Delete Profile</button>
        </form>
    </div>
@endsection
