<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header d-flex align-items-center justify-content-between w-100">
        <!-- Logo -->
        <div class="top-left-part">
            <a class="logo" href="{{ route('dashboard') }}">
                <b>
                    <img src="{{ asset('assets/plugins/images/admin-logo.png') }}" alt="logo" class="dark-logo" />
                    <img src="{{ asset('assets/plugins/images/admin-logo-dark.png') }}" alt="logo" class="light-logo" />
                </b>
                <span class="hidden-xs">
                    <img src="{{ asset('assets/plugins/images/admin-text.png') }}" alt="logo-text" class="dark-logo" />
                    <img src="{{ asset('assets/plugins/images/admin-text-dark.png') }}" alt="logo-text" class="light-logo" />
                </span>
            </a>
        </div>

        <!-- Navbar Right -->
        <ul class="nav navbar-top-links navbar-right pull-right align-items-center">
            <!-- Search -->
            <li>
                <form role="search" class="app-search hidden-sm hidden-xs m-r-10 d-flex align-items-center">
                    <input type="text" placeholder="Search..." class="form-control">
                    <button type="submit" class="btn btn-default ml-1"><i class="fa fa-search"></i></button>
                </form>
            </li>

            <!-- User Profile -->
            <li class="d-flex align-items-center">
                <a class="profile-pic d-flex align-items-center" href="#">
                    <img src="{{ asset('assets/plugins/images/users/varun.jpg') }}" alt="user-img" width="36" class="img-circle">
                    <b class="hidden-xs ml-2">Steave</b>
                </a>
            </li>

            <!-- Logout Button -->
            <li class="d-flex align-items-center ml-3">
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i> Đăng xuất
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
