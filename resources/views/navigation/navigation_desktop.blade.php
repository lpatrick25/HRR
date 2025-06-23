<div class="main-menu-area mg-tb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs custom-menu-wrap">
                    @if (auth()->user()->user_role === 'Owner')
                        {{--  --}}
                        <li class="@yield('active_home')">
                            <a href="{{ route('owner-dashboard') }}"><i class="fa fa-home"></i> Home</a>
                        </li>
                        <li class="@yield('active_transactions')">
                            <a data-toggle="tab" href="#tab_transactions"><i class="fa fa-exchange"></i> Transactions</a>
                        </li>
                        <li class="@yield('active_hotel')">
                            <a href="{{ route('owner-hotelManagement') }}"><i class="fa fa-building"></i> Hotel
                                Management</a>
                        </li>
                        <li class="@yield('active_resort')">
                            <a href="{{ route('owner-resortManagement') }}"><i class="fa fa-sun-o"></i> Resort
                                Management</a>
                        </li>
                        <li class="@yield('active_food')">
                            <a href="{{ route('owner-foodManagement') }}"><i class="fa fa-cutlery"></i> Food
                                Management</a>
                        </li>
                        <li class="@yield('active_billings')">
                            <a data-toggle="tab" href="#tab_billings"><i class="fa fa-credit-card"></i> Billings</a>
                        </li>
                        <li class="@yield('active_users')">
                            <a href="{{ route('owner-userManagement') }}"><i class="fa fa-users"></i> User
                                Management</a>
                        </li>
                        <li class="@yield('active_reports')">
                            <a data-toggle="tab" href="#tab_reports"><i class="fa fa-credit-card"></i> Reports</a>
                        </li>
                    @endif
                    @if (auth()->user()->user_role === 'Front Desk - Hotel')
                        {{--  --}}
                        <li class="@yield('active_home')">
                            <a href="{{ route('hotel-dashboard') }}"><i class="fa fa-home"></i> Home</a>
                        </li>
                        <li class="@yield('active_transactions')">
                            <a data-toggle="tab" href="#tab_transactions"><i class="fa fa-exchange"></i>
                                Transactions</a>
                        </li>
                        <li class="@yield('active_hotel')">
                            <a href="{{ route('hotel-hotelManagement') }}"><i class="fa fa-building"></i> Hotel
                                Management</a>
                        </li>
                    @endif
                    @if (auth()->user()->user_role === 'Front Desk - Resort')
                        {{--  --}}
                        <li class="@yield('active_home')">
                            <a href="{{ route('resort-dashboard') }}"><i class="fa fa-home"></i> Home</a>
                        </li>
                        <li class="@yield('active_transactions')">
                            <a data-toggle="tab" href="#tab_transactions"><i class="fa fa-exchange"></i>
                                Transactions</a>
                        </li>
                        <li class="@yield('active_resort')">
                            <a href="{{ route('resort-resortManagement') }}"><i class="fa fa-sun-o"></i> Resort
                                Management</a>
                        </li>
                    @endif
                    @if (auth()->user()->user_role === 'Front Desk - Food')
                        {{--  --}}
                        <li class="@yield('active_home')">
                            <a href="{{ route('food-dashboard') }}"><i class="fa fa-home"></i> Home</a>
                        </li>
                        <li class="@yield('active_transactions')">
                            <a data-toggle="tab" href="#tab_transactions"><i class="fa fa-exchange"></i>
                                Transactions</a>
                        </li>
                        <li class="@yield('active_resort')">
                            <a href="{{ route('food-foodManagement') }}"><i class="fa fa-sun-o"></i> Food
                                Management</a>
                        </li>
                    @endif
                    @if (auth()->user()->user_role === 'Admin')
                        {{--  --}}
                        <li class="@yield('active_home')">
                            <a href="{{ route('admin-dashboard') }}"><i class="fa fa-home"></i> Home</a>
                        </li>
                        <li class="@yield('active_users')">
                            <a href="{{ route('admin-userManagement') }}"><i class="fa fa-users"></i> User
                                Management</a>
                        </li>
                    @endif


                </ul>
                <div class="tab-content custom-menu-content">
                    <div id="tab_transactions" class="tab-pane tab-custon-menu-bg animated flipInX @yield('active_transactions')">
                        <ul class="main-menu-dropdown">
                            @if (auth()->user()->user_role === 'Owner')
                                {{--  --}}
                                <li><a href="{{ route('owner-hotelTransactions') }}"><i class="fa fa-bed"></i> Hotel
                                        Transaction</a></li>
                                <li><a href="{{ route('owner-resortTransactions') }}"><i class="fa fa-tree"></i> Resort
                                        Transaction</a></li>
                                <li><a href="{{ route('owner-foodTransactions') }}"><i class="fa fa-cutlery"></i> Food
                                        Transaction</a></li>
                            @endif
                            @if (auth()->user()->user_role === 'Front Desk - Hotel')
                                {{--  --}}
                                <li><a href="{{ route('hotel-hotelTransactions') }}"><i class="fa fa-bed"></i> Hotel
                                        Transaction</a></li>
                            @endif
                            @if (auth()->user()->user_role === 'Front Desk - Resort')
                                <li><a href="{{ route('resort-resortTransactions') }}"><i class="fa fa-tree"></i>
                                        Resort
                                        Transaction</a></li>
                                {{--  --}}
                            @endif
                            @if (auth()->user()->user_role === 'Front Desk - Food')
                                <li><a href="{{ route('food-foodTransactions') }}"><i class="fa fa-tree"></i>
                                        Food
                                        Transaction</a></li>
                                {{--  --}}
                            @endif
                        </ul>
                    </div>
                    <div id="tab_billings" class="tab-pane tab-custon-menu-bg animated flipInX @yield('active_billings')">
                        <ul class="main-menu-dropdown">
                            @if (auth()->user()->user_role === 'Owner')
                                {{--  --}}
                                <li><a href="{{ route('owner-hotelBillings') }}"><i class="fa fa-bed"></i> Hotel
                                        Billings</a></li>
                                <li><a href="{{ route('owner-resortBillings') }}"><i class="fa fa-tree"></i> Resort
                                        Billings</a></li>
                                <li><a href="{{ route('owner-foodBillings') }}"><i class="fa fa-cutlery"></i> Food
                                        Billings</a></li>
                            @endif
                            @if (auth()->user()->user_role === 'Front Desk - Hotel')
                                {{--  --}}
                                <li><a href="{{ route('hotel-hotelBillings') }}"><i class="fa fa-bed"></i> Hotel
                                        Billings</a></li>
                            @endif
                            @if (auth()->user()->user_role === 'Front Desk - Resort')
                                {{--  --}}
                                <li><a href="{{ route('resort-resortBillings') }}"><i class="fa fa-tree"></i> Resort
                                        Billings</a></li>
                            @endif
                        </ul>
                    </div>
                    <div id="tab_reports" class="tab-pane tab-custon-menu-bg animated flipInX @yield('active_reports')">
                        <ul class="main-menu-dropdown">
                            @if (auth()->user()->user_role === 'Owner')
                                {{--  --}}
                                <li><a href="{{ route('hotel.reports.index') }}"><i class="fa fa-bed"></i> Hotel
                                        Reports</a></li>
                                <li><a href="{{ route('resort.reports.index') }}"><i class="fa fa-tree"></i>
                                        Resort
                                        Reports</a></li>
                                <li><a href="{{ route('food.reports.index') }}"><i class="fa fa-cutlery"></i>
                                        Food
                                        Reports</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
