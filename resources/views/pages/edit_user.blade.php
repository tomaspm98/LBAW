@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Profile</h1>
        <form action="{{ route('user.update', $member->user_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your new username"> 
            </div>

            <div class="mb-3">
                <label for="user_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Enter your new email"> 
            </div>

            <div class="mb-3">
                <label for="user_password" class="form-label">Password</label>
                <input type="password" class="form-control" id="user_password" name="user_password" placeholder="Enter your new password"> 
            </div>
            
            <div class="mb-3">
                <label for="user_password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="user_password2" name="user_password_confirmation" placeholder="Confirm your new password"> 
            </div>

            <div class="mb-3">
                <label for="user_birthdate" class="form-label">Birthdate</label>
                <input type="date" id="user_birthdate" name="user_birthdate" class="form-control"> 
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
        
        <form class="my-2" action="{{ route('user.delete', $member->user_id) }}" method="post">
            @csrf
            @method('delete')
        
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your profile?')">Delete Profile</button>
        </form>
    </div>
@endsection
