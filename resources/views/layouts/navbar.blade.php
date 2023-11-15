<nav class="">

    <div class="nav_logo">
        <a href="{{ url ('/') }}">
            <!-- <img id="logo-img-header" src="" alt="QueryStack"> -->
            <h1>QueryStack</h1>
        </a>
    </div>

    <div class="search_container">
        <form action="" method="GET">
            <input type="text" name="search" value="" placeholder="Search...">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="nav_buttons_container">
        <ul>
            @if (false) <!--todo: user athenticated-->
            <li>
                <a href="{{ route('logout') }}"> 
                    <p> Logout </p>
                    (autenticated username...)
                </a>
            </li>
            @else
            <li>
                <a href="">Login</a>
            </li>
            <li>
                <a href="">Register</a>
            </li>
            @endif
        </ul>
    </div>
</nav>


