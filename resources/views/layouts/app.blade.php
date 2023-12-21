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
        <link href="{{ url('css/tags.css') }}" rel="stylesheet">
        <link href="{{ url('css/notification.css') }}" rel="stylesheet">
        <link href="{{ url('css/blocked_users.css') }}" rel="stylesheet">

        <script src="https://kit.fontawesome.com/03bf23ebdb.js" crossorigin="anonymous"></script>
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
        <!-- jQuery, Popper.js and Bootstrap scripts-->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>   
        <script type="text/javascript" src={{ url('js/app.js') }} defer></script>
        @if (Auth::check())
        <script type="text/javascript" src={{ url('js/notification.js') }} defer></script>
        @endif
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
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script>
            // Enable pusher logging - don't include this in production
            //Pusher.logToConsole = true;
        
            var pusher = new Pusher('833280c9b0db39c0f30d', {
                cluster: 'eu'
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
        <footer class="bg-dark text-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="site-motto mb-0">The best Q&A Platform for technology questions</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="d-flex justify-content-center justify-content-md-end">
                    <p class="mb-0">&copy; Query Stack!</p>
                    <div class="site-map ms-3">
                        <a class="btn btn-outline-light me-2 mb-md-0" href="{{ route('about') }}">About</a>
                        <a class="btn btn-outline-light me-2 mb-md-0" href="{{ route('about') }}#platform-contacts">Contacts</a>
                        <a class="btn btn-outline-light" href="{{ route('about') }}#faqs">FAQ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

    </body>
</html>