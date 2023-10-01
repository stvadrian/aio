<header class="app-header border-bottom border-accent border-2">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav align-items-center">
            <li class="nav-item d-flex">
                <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2 text-accent"></i>
                </a>
                <a class="nav-link" href="{{ url('/dashboard') }}">
                    <i class="ti ti-home text-accent"></i>
                </a>
            </li>
            <li class="nav-item fs-6 ms-3 fw-bold text-accent">
                {{ auth()->user()->cabang->nm_cabang }}
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <span class="d-none d-sm-block text-accent">{{ auth()->user()->nm_user }}</span>
                @if (session()->has('impersonator'))
                    <sup><span class="bg-primary rounded-pill text-white py-1 px-2 ms-1">Impersonate</span></sup>
                @endif
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon-hover position-relative" href="javascript:void(0)" id="drop2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->preview_profile }}" alt="" width="35" height="35"
                            class="rounded-circle">
                    </a>
                    @if (count(auth()->user()->unreadNotifications) > 0)
                        <div class="notification bg-accent rounded-circle"></div>
                    @endif
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                        <div class="message-body">
                            <span class="d-flex d-sm-none align-items-center gap-2 dropdown-item fw-bold">
                                <i class="ti ti-user fs-6"></i> {{ auth()->user()->nm_user }}
                            </span>
                            <a href="{{ url('/profile') }}" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-settings fs-6"></i>
                                <p class="mb-0 fs-3">Account Settings</p>
                            </a>
                            <a href="{{ url('/message') }}" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-message fs-6"></i>
                                <p class="mb-0 fs-3">Message</p>
                                @if (auth()->user()->unreadNotifications)
                                    <span class="badge rounded-pill bg-accent">
                                        {{ count(auth()->user()->unreadNotifications) }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ url('/toggle-darkmode') }}"
                                class="d-flex align-items-center gap-2 dropdown-item">
                                @if (session()->has('darkmode'))
                                    <i class="ti ti-sun fs-6"></i>
                                @else
                                    <i class="ti ti-moon fs-6"></i>
                                @endif
                                <p class="mb-0 fs-3">Change to {{ session()->has('darkmode') ? 'Light' : 'Dark' }} Theme
                                </p>
                            </a>
                            @if (session()->has('impersonator'))
                                <a href="{{ url('/user/stop-impersonate') }}"
                                    class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-power fs-6"></i>
                                    <p class="mb-0 fs-3">Stop Impersonation</p>
                                </a>
                            @endif
                            <form action="{{ url('/logout') }}" method="post" id="logout_form"
                                class="d-block mx-3 mt-2">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary w-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
