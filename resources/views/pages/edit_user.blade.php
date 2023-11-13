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
                <input class="form-control datepicker" id="user_birthdate" name="user_birthdate" placeholder="Enter your birthdate"> 
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
@endsection
