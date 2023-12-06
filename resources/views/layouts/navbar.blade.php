<?php 
use App\Models\Admin; 
use App\Models\Moderator; 

?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ url ('/') }}" ><h1>QueryStack!</h1></a>

    @if(\Request::route()->getName() !== "login" && \Request::route()->getName() !== "register")
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    @endif
          
    @if(\Request::route()->getName() !== "search")
    <div class="nav_search_container align-items-center">
        <form class="form-inline my-2 my-lg-0 align-items-center m-0" action="{{ route('search') }}" method="GET">
        <input class="form-control mr-sm-2 h-50" style="min-width: 150px" name="search" value="{{ request('search') }}" type="search" placeholder="Search..." aria-label="Search">
        <button class="btn btn-outline-success text-dark p-2 rounded-5" type="submit"><i class="bi bi-search p-1"></i></button>
        </form>
    </div>
    @endif

    <div class="collapse navbar-collapse nav_buttons" id="navbarSupportedContent">

        <ul class="navbar-nav">

            @if (Auth::guest())
            <li class="nav-item active p-1">
                <a class="nav-link" class="login-button" href="{{ url('/login') }}"> Login </a> 
            </li>
            <li class="nav-item active p-1">
                <a class="nav-link" class="register-button" href="{{ url('/register') }}"> Register </a>
            </li>
            @endif

            @if (Auth::check())
            <li class="nav-item active">
                <a class="nav-link" href="">Notifications</a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span>{{ Auth::user()->username }}</span>
                </a>
                <div class="dropdown-menu dropdown_user_menu" aria-labelledby="navbarDropdown">

                    @if(Route::currentRouteName() === 'member.show' && Route::current()->parameter('user_id') == Auth::user()->user_id)
                        <a class="dropdown-item"  href="{{ route('member.edit', ['user_id' => Auth::user()->user_id]) }}">Edit Profile</a>
                    @else
                        <a class="dropdown-item"  href="{{ route('member.show', ['user_id' => Auth::user()->user_id]) }}">Profile</a>
                    @endif

                    @if (Auth::check() && Admin::where('user_id', Auth::user()->user_id)->exists() )
                    <div class="admin-area">
                        <div class="admin-buttons">
                            <a class="dropdown-item" href="{{ route('admin.users') }}">Assign Moderator</a>
                            <a class="dropdown-item" href=" {{ route('admin.moderators') }}">Remove Moderator</a>
                            <a class="dropdown-item" href="{{ route('tags.show') }}">Tags</a>
                        </div>
                    </div>                   
                    @elseif (Auth::check() && Moderator::where('user_id', Auth::user()->user_id)->exists() )
                    <div class="admin-area">
                        <div class="admin-buttons">
                            <a class="dropdown-item" href="{{ route('reports') }}">Reports</a>
                        </div>
                    </div>  
                    @endif  

                    <div class="dropdown-divider"></div>
                    <form class="m-0" action="{{ url('/logout') }}" method="post">
                        @csrf
                        <button class="dropdown-item text-danger" type="submit" class="button">Logout</button>
                    </form>
                
                </div>
            </li>
            @endif

        </ul>

    </div>
</nav>



    <div class="nav_buttons_container">
     <div class="header-buttons">



    </div>



