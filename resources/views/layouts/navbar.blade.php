<?php 
use App\Models\Admin; 
use App\Models\Moderator; 
use App\Models\Member;
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light flex-container">
    <a class="navbar-brand" href="{{ url ('/') }}"><h1>QueryStack!</h1></a>
    
    @if(\Request::route()->getName() !== "login" && \Request::route()->getName() !== "register")
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    @endif
    
    @if(\Request::route()->getName() !== "search")
        <div class="nav_search_container align-items-center hover-container-down" >
            <form class="form-inline my-2 my-lg-0 align-items-center m-0" action="{{ route('search') }}" method="GET">
                <input class="form-control mr-sm-2 h-50" style="min-width: 150px" name="search" value="{{ request('search') }}" type="search" placeholder="Search..." aria-label="Search">
                <button class="btn btn-outline-success text-dark p-2 rounded-5" type="submit"><i class="bi bi-search p-1"></i></button>
                <div class="icon-container">
                    <i class="fa-solid fa-circle-question" style="color: #0f4aa8;"></i>
                    <span class="hover-text-down">Here you can search for the questions you want using words, and having the chance of finding one that solves your problem.</span>
                </div>
            </form>
        </div>
    @endif


    <div class="collapse navbar-collapse nav_buttons" id="navbarSupportedContent">

        @if (Auth::check())
        @include('partials.notifications')
        @endif
        <ul class="navbar-nav">

            @if (Auth::guest())
            <li class="nav-item active p-1">
                <a class="nav-link login-button" href="{{ url('/login') }}"> Login </a> 
            </li>
            <li class="nav-item active p-1">
                <a class="nav-link register-button" href="{{ url('/register') }}"> Register </a>
            </li>
            @endif

            @if (Auth::check())
            <li class="nav-item active">
            </li>
            

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                </a>
                    <span>{{ Auth::user()->username }}</span>
                <div class="dropdown-menu dropdown_user_menu" aria-labelledby="navbarDropdown">

                        <a class="dropdown-item"  href="{{ route('member.edit', ['user_id' => Auth::user()->user_id]) }}">Edit Profile</a>
                    @if(Route::currentRouteName() === 'member.show' && Route::current()->parameter('user_id') == Auth::user()->user_id)
                    @else
                        <a class="dropdown-item"  href="{{ route('member.show', ['user_id' => Auth::user()->user_id]) }}">Profile</a>
                    @endif

                    @if (Auth::check() && Admin::where('user_id', Auth::user()->user_id)->exists() )
                    <div class="admin-area">
                        <div class="admin-buttons">
                            <a class="dropdown-item" href="{{ route('admin.users') }}">Assign Moderator</a>
                            <a class="dropdown-item" href=" {{ route('admin.moderators') }}">Remove Moderator</a>
                            <a class="dropdown-item" href="{{ route('tags.show') }}">Tags</a>
                            <a class="dropdown-item" href="{{ route('reports') }}">Reports</a>
                            <a class="dropdown-item" href="{{ route('user.blocked') }}">Blocked Users</a>
                        </div>
                    </div>                   
                    @elseif (Auth::check() && Moderator::where('user_id', Auth::user()->user_id)->exists() )
                    <div class="admin-area">
                        <div class="admin-buttons">
                            <a class="dropdown-item" href="{{ route('reports') }}">Reports</a>
                            <a class="dropdown-item" href="{{ route('user.blocked') }}">Blocked Users</a>
                        </div>
                    </div>  
                    @endif  

                    <div class="dropdown-divider"></div>
                    <form class="m-0" action="{{ url('/logout') }}" method="post">
                        @csrf
                        <button class="dropdown-item text-danger" type="submit">Logout</button>
                    </form>
                
                </div>
            </li>
            @endif

        </ul>

    </div>
</nav>


