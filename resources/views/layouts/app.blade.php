<?php 
use App\Models\Admin; 
?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <link href="{{ url('css/about.css') }}" rel="stylesheet">
        <link href="{{ url('/css/question-page.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (window.location.pathname === '/about' && window.location.hash === '#platform-contacts') {
                    const contactsSection = document.getElementById('contacts');
    
                    if (contactsSection) {
                        // Scroll to the "Contacts" section with smooth behavior
                        contactsSection.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }
            });
        </script>
    </head>
    <body>
        <main>
            <header>
                <h1>
                    <a href="{{ url('/login') }}">Query Stack!</a>
                </h1>
                <div class="search-bar">
                    <form action="/search" method="get">
                        <input type="text" name="query" placeholder="Search...">
                        <button type="submit">Search</button>
                    </form>
                </div>
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
                        </div>
                    </div>                    @endif  
                    <a class="button" href="">Notifications</a>
                    @if(Route::currentRouteName() === 'member.show' && Route::current()->parameter('user_id') == Auth::user()->user_id)
                        <a class="button" href="{{ route('member.edit', ['user_id' => Auth::user()->user_id]) }}">Edit Profile</a>
                    @else
                        <a class="button" href="{{ route('member.show', ['user_id' => Auth::user()->user_id]) }}">User Profile</a>
                    @endif
                    <span>{{ Auth::user()->name }}</span>
                @endif
            
                </div>
            </header>
            <section id="content">
                @yield('content')
            </section>
        </main>
        <footer>
            <p class="site-moto">The best Q&A Platform for thechnology questions</p>
            <p>&copy; Query Stack!</p>
            <div class="site-map">
                <a class="button" href="{{ route('about') }}"> About </a>
                <a class="button" href="{{ route('about') }}#platform-contacts"> Contacts </a>
                
            </div>
        </footer>
    </body>
</html>