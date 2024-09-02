<nav class="navbar fixed-top px-0 shadow-sm bg-white">
    <div class="container-fluid">

        <a class="navbar-brand" href="#">
            <span class="icon-nav m-0 h5" onclick="MenuBarClickHandler()">
                <img class="nav-logo-sm mx-2" src="{{ asset('images/menu.svg') }}" alt="logo" />
            </span>
            {{-- <img class="nav-logo  mx-2" src="{{ asset('uploads/logo/logo') . $id . 'png' }}" alt="logo" /> --}}
            <img class="nav-logo  mx-2"
                src="@if (file_exists(public_path('uploads/logo/logo' . $id . '.png'))) {{ asset('uploads/logo/logo' . $id . '.png') }} @else {{ asset('uploads/logo/logo.png') }} @endif"
                alt="logo" />
        </a>

        <div class="float-right h-auto d-flex">
            <div class="user-dropdown">
                <img class="icon-nav-img" src="{{ asset('images/user.webp') }}" alt="" />
                <div class="user-dropdown-content ">
                    <div class="mt-4 text-center">
                        <img class="icon-nav-img" src="{{ asset('images/user.webp') }}" alt="" />
                        <h6>User Name</h6>
                        <hr class="user-dropdown-divider  p-0" />
                    </div>
                    <a href="{{ route('profile') }}" class="side-bar-item">
                        <span class="side-bar-item-caption">Profile</span>
                    </a>
                    <a href="{{ route('userLogout') }}" class="side-bar-item">
                        <span class="side-bar-item-caption">Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
