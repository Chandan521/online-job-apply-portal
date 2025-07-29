{{-- Recruiter Desktop Navbar --}}
<nav class="navbar navbar-expand-lg navbar-light navbar-custom shadow-sm py-3 fixed-top d-none d-lg-flex"
    id="recruiterNavbar" aria-label="Recruiter desktop navigation">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold fs-4 d-flex align-items-center" href="{{ route('home') }}"
            aria-label="HireMe - Home">
            <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('storage/defaults/logo.png') }}"
                alt="{{ setting('site_name') ?? 'HireMe Logo' }}" style="height: 32px; margin-right: 10px;">
            {{ setting('site_name', 'HireMe') }}
        </a>

        <div class="collapse navbar-collapse show" id="navbarRecruiter">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-4">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('hire') ? 'active' : '' }}"
                        href="{{ route('hire') }}">Post a Job</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('recruiter_dashboard') }}">Find CVs</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('products') ? 'active' : '' }}"
                        href="{{ route('products') }}">Products</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('resources') ? 'active' : '' }}"
                        href="{{ route('resources') }}">Resources</a></li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                {{-- Shared content like dashboard/help/find job/avatar dropdown --}}
                @php $user = Auth::user(); @endphp

                @if (in_array(optional($user)->role, ['recruiter', 'recruiter_assistant']))
                    <li class="nav-item">
                        <a href="{{ route('recruiter_dashboard') }}" class="nav-link"><i class="fas fa-home me-1"></i>
                            Dashboard</a>
                    </li>
                @endif

                <li class="nav-item"><a href="{{ route('pages.show', 'help') }}" class="nav-link"><i
                            class="fas fa-question-circle me-1"></i> Help Center</a></li>
                <li class="nav-item"><a href="{{ route('home') }}" class="nav-link"><i
                            class="fas fa-briefcase me-1"></i> Find Job</a>
                </li>

                @if (in_array(optional($user)->role, ['recruiter', 'recruiter_assistant']))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="dropdownUser"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User menu"
                            style="background: none; border: none; padding: 0;">

                            {{-- Avatar image based on Auth user name --}}
                            @php
                                $user = Auth::user();
                                $avatarUrl =
                                    'https://ui-avatars.com/api/?name=' .
                                    urlencode($user->name ?? 'User') .
                                    '&background=6c757d&color=fff&size=40';
                            @endphp

                            <img src="{{ $avatarUrl }}" alt="{{ $user->name ?? 'User' }}"
                                style="width: 40px; height: 40px; border-radius: 50%;">
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="dropdownUser"
                            style="min-width: 250px">
                            <div class="fw-bold mb-2">{{ $user->email }}</div>
                            <li>
                                <h6 class="dropdown-header">Billing</h6>
                            </li>
                            <li><a class="dropdown-item d-flex align-items-center" href="#"><i
                                        class="bi bi-receipt me-2"></i> Invoices</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" href="#"><i
                                        class="bi bi-credit-card me-2"></i> Payment Methods</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <h6 class="dropdown-header">Team</h6>
                            </li>
                            <li>
                                @if (hasAccess('manage_team'))
                                    <a class="dropdown-item d-flex align-items-center"
                                        href="{{ route('recruiter.subuser.index') }}">
                                        <i class="bi bi-people me-2"></i> Manage Access
                                    </a>
                                @else
                                    <span class="dropdown-item d-flex align-items-center text-muted"
                                        style="pointer-events: none; cursor: not-allowed; opacity: 0.6;"
                                        title="Access restricted">
                                        <i class="bi bi-people me-2"></i> Manage Access
                                    </span>
                                @endif

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <h6 class="dropdown-header">Account</h6>
                            </li>
                            <li><a class="dropdown-item d-flex align-items-center"
                                    href="{{ route('recruiter.account.settings') }}"><i class="bi bi-gear me-2"></i> My
                                    Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li class="text-center">
                                <a href="#" class="dropdown-item fw-semibold"
                                    aria-label="Visit Jobseekers site">Visit
                                    {{ setting('site_name', config('app.name')) }} for
                                    Recruiter</a>
                            </li>
                            <li class="text-center">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-bold">Sign out</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('recruiter.login') }}" class="btn btn-outline-primary btn-sm">Sign In</a>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</nav>

{{-- Recruiter Mobile/Tablet Navbar --}}
<nav class="navbar navbar-light bg-white shadow-sm px-1 py-3 fixed-top d-lg-none" id="mobileRecruiterNavbar"
    aria-label="Recruiter mobile navigation">
    <div class="container-fluid px-3 d-flex justify-content-between align-items-center">
        {{-- <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('storage/defaults/logo.png') }}"
                alt="Logo" style="height: 30px;">
        </a> --}}
        @if (in_array(optional($user)->role, ['recruiter', 'recruiter_assistant']))
            <a href="{{ route('hire') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('hire') ? 'text-primary fw-bold' : 'text-dark' }}"><i
                    class="fas fa-house fs-5"></i></a>

            <a href="{{ route('recruiter_dashboard') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('recruiter_dashboard') ? 'text-primary fw-bold' : 'text-dark' }}"><i
                    class="fas fa-tachometer-alt fs-5"></i></a>

            <a href="{{ route('products') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('products') ? 'text-primary fw-bold' : 'text-dark' }}"><i
                    class="fas fa-shopping-bag fs-5"></i>
            </a>
            <a href="{{ route('resources') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('resources') ? 'text-primary fw-bold' : 'text-dark' }}"><i
                    class="fas fa-folder fs-5"></i>
            </a>

            <a class="text-dark text-center flex-fill" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#recruiterMobileOffcanvas" aria-controls="recruiterMobileOffcanvas"
                aria-label="Toggle mobile menu">
                <i class="fas fa-bars fs-5"></i>
            </a>
        @else
            <a href="{{ route('hire') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('hire') ? 'text-primary fw-bold' : 'text-dark' }}"><i
                    class="fas fa-house fs-5"></i></a>

            <a href="{{ route('products') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('products') ? 'text-primary fw-bold' : 'text-dark' }}"><i
                    class="fas fa-shopping-bag fs-5"></i></a>

            <a href="{{ route('resources') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('resources') ? 'text-primary fw-bold' : 'text-dark' }}"><i
                    class="fas fa-folder fs-5"></i></a>

            <a href="{{ route('home') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('home') ? 'text-primary fw-bold' : 'text-dark' }}"><i
                    class="fas fa-clipboard-list fs-5"></i>
            </a>

            <a class="text-dark text-center flex-fill" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#recruiterMobileOffcanvas" aria-controls="recruiterMobileOffcanvas"
                aria-label="Toggle mobile menu">
                <i class="fas fa-bars fs-5"></i>
            </a>
        @endif
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="recruiterMobileOffcanvas"
        aria-labelledby="recruiterMobileOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="recruiterMobileOffcanvasLabel">{{ setting('site_name') ?? 'Site Name' }}
            </h5>

            <a class="navbar-brand d-flex align-items-center fw-bold text-primary fs-5 me-3"
                href="{{ route('hire') }}">

                <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('storage/defaults/logo.png') }}"
                    alt="{{ setting('site_name') ?? 'HireMe Logo' }}" style="height: 60px; width: auto;">
            </a>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav">
                @if (in_array(optional($user)->role, ['recruiter', 'recruiter_assistant']))
                    <li class="px-3 pt-2 d-flex align-items-center gap-2">
                        {{-- Profile Image --}}
                        <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}"
                            alt="{{ $user->name }}" class="rounded-circle"
                            style="width: 45px; height: 45px; object-fit: cover;">

                        {{-- User Info --}}
                        <div class="d-flex flex-column">
                            <span class="fw-semibold text-dark"
                                style="font-size: 0.95rem;">{{ $user->name }}</span>
                            <span class="text-muted small">{{ $user->email }}</span>
                        </div>
                    </li>
                    <hr>
                    <li class="nav-item">
                        @if (hasAccess('manage_team'))
                            <a class="nav-link" href="{{ route('recruiter.subuser.index') }}">
                                <i class="fas fa-user-shield me-2"></i> Manage Access
                            </a>
                        @else
                            <a class="nav-link disabled">
                                <i class="fas fa-user-shield me-2"></i> Manage Access
                            </a>
                        @endif
                    </li>

                    <hr>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('recruiter.account.settings') }}">
                            <i class="fas fa-cog me-2"></i> Account Settings
                        </a>
                    </li>
                    @foreach (\App\Models\StaticPage::all() as $page)
                        <hr>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pages.show', $page->slug) }}">
                                <i class="fas fa-file-alt me-2"></i> {{ $page->title }}
                            </a>
                        </li>
                    @endforeach
                    <hr>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="nav-link btn btn-link text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item ">
                        <a class="nav-link" href="{{ route('recruiter.login') }}">
                            <i class="fas fa-sign-in-alt me-2"></i> Sign In
                        </a>
                    </li>
                    <hr>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('recruiter.register') }}">
                            <i class="fas fa-user-tie me-2"></i> Register
                        </a>
                    </li>
                    @foreach (\App\Models\StaticPage::all() as $page)
                        <hr>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pages.show', $page->slug) }}">
                                <i class="fas fa-file-alt me-2"></i> {{ $page->title }}
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</nav>
