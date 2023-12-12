<?php 
use App\Models\Admin; 
use App\Models\Moderator; 
use App\Models\Member;
?>

<nav class="">

    <div class="nav_logo">
        <a href="{{ url ('/') }}">
            <!-- <img id="logo-img-header" src="" alt="QueryStack"> -->
            <h1>QueryStack!</h1>
        </a>
    </div>

    @if(\Request::route()->getName() !== "search")
    <div class="search_container hover-container-down">
        <form action="{{ route('search') }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." required>
            <span class="hover-text-down">Here you can search for the questions you want using words, and having the chance of finding one that solves your problem.</span>
            <button type="submit">Search</button>
        </form>
    </div>
    @endif

    <div class="nav_buttons_container">
     <div class="header-buttons">
        @if (Auth::guest())
            <a class="button" class="login-button" href="{{ url('/login') }}"> Login </a> 
            <a class="button" class="register-button" href="{{ url('/register') }}"> Register </a>
        @endif

        @if (Auth::check())
            <form action="{{ url('/logout') }}" method="post">
                @csrf
                <button type="submit" class="button">Logout</button>
            </form>
            @if (Auth::check() && Admin::where('user_id', Auth::user()->user_id)->exists() )
            <div class="admin-area">
                <div class="admin-buttons">
                    <a class="button" href="{{ route('admin.users') }}">Assign Moderator</a>
                    <a class="button" href=" {{ route('admin.moderators') }}">Remove Moderator</a>
                    <a class="button" href="{{ route('tags.show') }}">Tags</a>
                    <a class="button" href="{{ route('reports') }}">Reports</a>

                </div>
            </div>                   
            @elseif (Auth::check() && Moderator::where('user_id', Auth::user()->user_id)->exists() )
            <div class="admin-area">
                <div class="admin-buttons">
                    <a class="button" href="{{ route('reports') }}">Reports</a>
                </div>
            </div>  
            @endif  
            @include('partials.notifications')
                     
            @if(Route::currentRouteName() === 'member.show' && Route::current()->parameter('user_id') == Auth::user()->user_id)
                <a class="button" href="{{ route('member.edit', ['user_id' => Auth::user()->user_id]) }}">Edit Profile</a>
            @else
                <a class="button" href="{{ route('member.show', ['user_id' => Auth::user()->user_id]) }}">User Profile</a>
            @endif
            <span>{{ Auth::user()->username }}</span>
        @endif

    </div>
</nav>


