@php
    $user = Auth::user();
@endphp

{{-- ✅ Desktop Navbar --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3 py-2 sticky-top d-none d-lg-flex"
    role="navigation">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center fw-bold text-primary fs-5" href="{{ route('home') }}">
            <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('storage/defaults/logo.png') }}"
                alt="{{ setting('site_name') ?? 'HireMe Logo' }}" style="height: 60px; width: auto; margin-right: 0px;">
            {{ setting('site_name') ?? 'HireMe' }}
        </a>

        <div class="collapse navbar-collapse justify-content-between" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item mx-3">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i> Home
                    </a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link {{ request()->routeIs('company') ? 'active' : '' }}"
                        href="{{ route('company') }}">
                        <i class="fas fa-building me-1"></i> Company Reviews
                    </a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link {{ request()->routeIs('salaries') ? 'active' : '' }}"
                        href="{{ route('salaries') }}">
                        <i class="fas fa-money-bill-wave me-1"></i> Salary Guide
                    </a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link {{ request()->routeIs('blogs.index') || request()->routeIs('blog.show') ? 'active' : '' }}"
                        href="{{ route('blogs.index') }}">
                        <i class="fas fa-blog me-1"></i> Blogs
                    </a>
                </li>
                <li class="nav-item dropdown mx-3">
                    <a class="nav-link no-caret dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-file-alt me-1"></i> Pages
                    </a>
                    <ul class="dropdown-menu">
                        @foreach (\App\Models\StaticPage::all() as $page)
                            <li>
                                <a class="dropdown-item" href="{{ route('pages.show', $page->slug) }}">
                                    <i class="fas fa-angle-right me-1"></i> {{ $page->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-3">
                @if ($user && $user->role === 'job_seeker')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('saved') ? 'active' : '' }}"
                            href="{{ route('saved') }}">
                            <i class="fas fa-heart"></i>
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link {{ request()->routeIs('user.conversations*') ? 'active' : '' }}"
                            href="{{ route('user.conversations') }}">
                            <i class="fas fa-comment-dots"></i>
                            @if ($totalUnread > 0)
                                <span
                                    class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                                    {{ $totalUnread }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link {{ request()->routeIs('notifications') ? 'active' : '' }}"
                            href="{{ route('notifications') }}">
                            <i class="fas fa-bell"></i>
                            @if ($totalUnreadNotifications > 0)
                                <span
                                    class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                                    {{ $totalUnreadNotifications }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle no-caret d-flex align-items-center" href="#"
                            id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0d6efd&color=fff&size=128' }}"
                                alt="Profile" class="rounded-circle border"
                                style="width:32px; height:32px; object-fit:cover;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="px-3 pt-2 d-flex align-items-center text-success" style="font-size: 1rem;">
                                <i class="fas fa-user-circle me-2"></i>
                                <span class="fw-semibold">{{ $user->name }}</span>
                            </li>
                            <li class="px-3 pt-2 small text-muted">{{ $user->email }}</li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ url('user/settings?tab=profile') }}"><i
                                        class="fas fa-user me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="{{ url('user/settings?tab=reviews') }}"><i
                                        class="fas fa-star me-2"></i> My Reviews</a></li>
                            <li><a class="dropdown-item" href="{{ route('interviews') }}"><i
                                        class="fas fa-briefcase me-2"></i> Interview</a></li>
                            <li><a class="dropdown-item" href="{{ url('user/settings?tab=jobs') }}"><i
                                        class="fas fa-briefcase me-2"></i> My Jobs</a></li>
                            <li><a class="dropdown-item" href="{{ url('user/settings') }}"><i
                                        class="fas fa-cog me-2"></i> Settings</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.show', 'help') }}"><i
                                        class="fas fa-question-circle me-2"></i> Help</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.show', 'privacy') }}"><i
                                        class="fas fa-shield-alt me-2"></i> Privacy</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>
                                        Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('user_login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Sign In
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm ms-2" href="{{ route('hire') }}">
                            <i class="fas fa-briefcase me-1"></i> Employers/Post Job
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

{{-- ✅ Mobile Navbar --}}
<nav
    class="navbar navbar-light bg-white shadow-sm px-1 py-3 d-flex d-lg-none justify-content-between align-items-center sticky-top">
    <div class="d-flex align-items-center justify-content-between flex-grow-1 mx-2">
        @if ($user && $user->role === 'job_seeker')
            <a href="{{ route('home') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('home') ? 'text-primary fw-bold' : 'text-dark' }}"><i
                    class="fas fa-house fs-5"></i></a>
            <a href="{{ route('saved') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('saved') ? 'text-primary fw-bold' : 'text-dark' }}"><i
                    class="fas fa-bookmark fs-5"></i></a>
            <a href="{{ route('user.conversations') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('user.conversations') ? 'text-primary fw-bold' : 'text-dark' }}"><i
                    class="fas fa-envelope fs-5"></i></a>
            <a href="{{ route('notifications') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('notifications') ? 'text-primary fw-bold' : 'text-dark' }}"><i
                    class="fas fa-bell fs-5"></i></a>
            <a class="text-dark text-center flex-fill" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileMenu">
                <i class="fas fa-bars fs-5"></i>
            </a>
        @else
            <a href="{{ route('home') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('home') ? 'text-primary fw-bold' : 'text-dark' }}"><i
                    class="fas fa-house fs-5"></i></a>
            <a href="{{ route('blogs.index') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('blogs.index') ? 'text-primary fw-bold' : 'text-dark' }}"><i
                    class="fas fa-blog fs-5"></i></a>
            <a href="{{ route('company') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('company') ? 'text-primary fw-bold' : 'text-dark' }}">
                <i class="fas fa-building fs-5"></i></a>
            <a href="{{ route('salaries') }}"
                class="text-dark text-center flex-fill {{ request()->routeIs('salaries') ? 'text-primary fw-bold' : 'text-dark' }}">
                <i class="fas fa-money-bill-wave fs-5"></i></a>
            <a class="text-dark text-center flex-fill" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileMenu">
                <i class="fas fa-bars fs-5"></i>
            </a>
        @endif
    </div>

    {{-- Toggle for More --}}

</nav>

{{-- ✅ Mobile Offcanvas Menu --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header">

        <h5 class="offcanvas-title">{{ setting('site_name') ?? 'Site Name' }}</h5>
        <a class="navbar-brand d-flex align-items-center fw-bold text-primary fs-5 me-3" href="{{ route('home') }}">
            <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('storage/defaults/logo.png') }}"
                alt="{{ setting('site_name') ?? 'HireMe Logo' }}" style="height: 60px; width: auto;">
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav">
            @if ($user && $user->role === 'job_seeker')
                <li class="px-3 pt-2 d-flex align-items-center gap-3">
                    {{-- Profile Image --}}
                    <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}"
                        alt="{{ $user->name }}" class="rounded-circle"
                        style="width: 45px; height: 45px; object-fit: cover;">

                    {{-- User Info --}}
                    <div class="d-flex flex-column">
                        <span class="fw-semibold text-dark" style="font-size: 0.95rem;">{{ $user->name }}</span>
                        <span class="text-muted small">{{ $user->email }}</span>
                    </div>
                </li>

                <hr>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('user/settings?tab=profile') }}"><i
                            class="fas fa-user me-2"></i> Profile</a>
                </li>
                <hr>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('user/settings?tab=reviews') }}"><i
                            class="fas fa-star me-2"></i> My Reviews</a>
                </li>
                <hr>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('user/settings?tab=jobs') }}"><i
                            class="fas fa-briefcase me-2"></i> My Jobs</a>
                </li>
                <hr>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('user/settings') }}">
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
                    <a class="nav-link" href="{{ route('user_login') }}">
                        <i class="fas fa-sign-in-alt me-2"></i> Sign In
                    </a>
                </li>
                <hr>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('hire') }}">
                        <i class="fas fa-user-tie me-2"></i> Employers / Post Job
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
