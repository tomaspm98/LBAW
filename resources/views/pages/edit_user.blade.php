@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Profile</h1>
        <form action="{{ route('user.update', $member->user_id) }}" method="POST" enctype="multipart/form-data">
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
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your new password"> 
            </div>
            


            <div class="form-group">
                <label for="user_password_confirmation">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm your new password"> 
            </div>


            <div class="form-group">
                <label for="user_birthdate">Birthdate</label>
                <input type="date" id="user_birthdate" name="user_birthdate" placeholder="Enter your birthdate"> 
            </div>

            <div class="form-group">
                <label for="picture" class="form-label">Profile Picture</label>
                <input id="picture" type="file" name="picture" value="{{ old('picture') }}" accept="image/png" class="form-control">
                <p class="accepted-formats">Accepted formats: png</p>
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
        <div class="button-container d-flex justify-content-around" >
            <button type="submit" class="btn btn-primary">Update Profile</button>
            
        </div>
        </form>
        <div class="button-container d-flex justify-content-around" >
            <form action="{{ route('user.delete', $member->user_id) }}" method="POST">
                @csrf
                @method('delete')
                <button class="btn btn-primary" style="background-color: #cc0033; border-color:#cc0033;" type="submit" onclick="return confirm('Are you sure you want to delete your profile?')">Delete Profile</button>
            </form>
            
        </div>

    </div>
@endsection
