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

        <title> QueryStack! </title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <link href="{{ url('css/about.css') }}" rel="stylesheet">
        <link href="{{ url('/css/question-page.css') }}" rel="stylesheet">
        <link href="{{ url('/css/home-page.css') }}" rel="stylesheet">
        <link href="{{ url('css/search-page.css') }}" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
                @include ('layouts.navbar')
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