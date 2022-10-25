<nav class="navbar navbar-expand navbar-light navbar-bg shadow-sm" style="font-family: 'Kanit', sans-serif;">
    <a class="sidebar-toggle js-sidebar-toggle">
        <img src="https://img.icons8.com/external-febrian-hidayat-flat-febrian-hidayat/64/000000/external-menu-bar-ui-essential-febrian-hidayat-flat-febrian-hidayat.png"/>
    </a>
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">
            <li class="nav-item dropdown pt-1">
                <div class="avatar-initials" width="25" height="25" data-name="{{Auth::user()->name}}"></div>
            </li>
            <a class="dropdown-item" href="{{route('logout')}}">
                <img src="https://img.icons8.com/fluency-systems-filled/30/E74C3C/exit.png" width="20px"/>
                Sign Out
            </a>
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle" data-feather="settings"></i>
                </a>
                <a class="nav-link dropdown-toggle d-none d-sm-inline-block pt-1" href="#" data-bs-toggle="dropdown">
                    <span class="text-dark">{{Auth::user()->name}}  </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <img src="https://img.icons8.com/fluency-systems-filled/30/E74C3C/exit.png" width="20px"/>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>