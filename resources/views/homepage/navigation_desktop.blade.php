<header class="header">
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <div class="header__logo">
                    <a href="#">
                        <img src="{{ asset('homepage/img/logo.png') }}" alt="Logo" style="height: 40px;">
                    </a>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="header__nav">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li class="@yield('active-homepage')">
                                <a href="{{ route('homepage-homepage') }}">Homepage</a>
                            </li>
                            <li class="@yield('active-categories')">
                                <a href="{{ route('homepage-categories') }}">Categories <span
                                        class="arrow_carrot-down"></span></a>
                                <ul class="dropdown">
                                    <li><a href="{{ route('homepage-categoryFood') }}">Restaurant</a></li>
                                    <li><a href="{{ route('homepage-categoryHotel') }}">Hotel Room</a></li>
                                    <li><a href="{{ route('homepage-categoryResort') }}">Resort Cottage</a></li>
                                </ul>
                            </li>
                            <li class="@yield('active-experience')">
                                <a href="{{ route('homepage-experience') }}">Experience</a>
                            </li>
                            <li class="@yield('active-book')">
                                <a href="#">Book Now <span class="arrow_carrot-down"></span></a>
                                <ul class="dropdown">
                                    <li><a href="{{ route('homepage-bookHotel') }}">Hotel Room</a></li>
                                    <li><a href="{{ route('homepage-bookResort') }}">Resort Cottage</a></li>
                                </ul>
                            </li>
                            <li class="@yield('active-contact')">
                                <a href="{{ route('homepage-contact') }}">Contacts</a>
                            </li>
                            @if (auth()->check())
                                <li>
                                    <a href="#">{{ auth()->user()->first_name }}
                                        {{ auth()->user()->last_name }}<span class="arrow_carrot-down"></span></a>
                                    <ul class="dropdown">
                                        @if (in_array(auth()->user()->user_role, ['Owner', 'Front Desk - Hotel', 'Front Desk - Resort']))
                                            <li><a href="{{ route('owner-dashboard') }}">Dashboard</a></li>
                                        @else
                                            {{-- <li><a href="#">My Reservation</a></li> --}}
                                        @endif

                                        <li><a href="#" id="logout">Logout</a></li>
                                    </ul>

                                </li>
                            @else
                                <li class="@yield('active-login')"><a href="{{ route('homepage-login') }}">Login</a></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div id="mobile-menu-wrap"></div>
    </div>
</header>
