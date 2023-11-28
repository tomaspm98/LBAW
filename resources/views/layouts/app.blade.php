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
        <link href="{{ url('css/admin-page.css') }}" rel="stylesheet">
        <link href="{{ url('css/user.css') }}" rel="stylesheet">
        <link href="{{ url('css/report.css') }}" rel="stylesheet">

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

        <!-- jQuery, Popper.js and Bootstrap scripts-->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        

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