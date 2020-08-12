<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SponsorsHub  @yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @section('headers')

    @endsection

</head>
<body>

<section class="section">
    <div class="container">

        <nav class="navbar" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item" href="https://github.com/erikaheidi/sponsorshub">
                    <h1>SponsorsHub</h1>
                </a>

                <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navbarBasicExample" class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item">
                        Home
                    </a>

                    <a class="navbar-item">
                        Documentation
                    </a>

                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">
                            More
                        </a>

                        <div class="navbar-dropdown">
                            <a class="navbar-item">
                                About
                            </a>
                            <a class="navbar-item">
                                Jobs
                            </a>
                            <a class="navbar-item">
                                Contact
                            </a>
                            <hr class="navbar-divider">
                            <a class="navbar-item">
                                Report an issue
                            </a>
                        </div>
                    </div>
                </div>

                <div class="navbar-end">
                    <div class="navbar-item">
                        <div class="buttons">
                            @if(Auth::user())
                            <a class="button is-primary">
                                <strong>LOGGED IN as {{ Auth::user()->login }}</strong>
                            </a>
                            <a class="button is-light" href="{{ route('logout') }}">
                                Logout
                            </a>
                            @else
                            <a class="button is-light" href="{{ route('login.github') }}">
                                <i class="fa fa-github"></i>  Log in via GitHub
                            </a>
                            <a class="button is-light" href="{{ route('login.twitch') }}">
                                <i class="fa fa-twitch"></i>  Log in via Twitch
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="columns">
            <div class="column">
                @section('content')
                    <h1>SponsorsHub</h1>
                @show
            </div>

            <div class="column is-one-quarter">
                <h3>About</h3>

                <h3>Links</h3>

            </div>
        </div>

    </div>

   </section>
</body>
</html>
