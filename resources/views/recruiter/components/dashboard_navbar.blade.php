<!-- Top Navbar -->
<nav class="navbar top-navbar fixed-top px-3">
    <div class="container-fluid">
        <!-- Left Side: Mobile Menu Button and Logo -->
        <div class="d-flex align-items-center">
            <button class="btn mobile-menu-btn me-2 d-lg-none">
                <i class="bi bi-list" style="font-size: 1.5rem;"></i>
            </button>
            <a class="navbar-brand me-4 d-none d-lg-block" href="{{ route('recruiter_dashboard') }}">
                <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('storage/defaults/logo.png') }}"
                    width="50" height="50" class="rounded me-2">
                <span class="fw-bold"
                    style="color: var(--primary-color);">{{ setting('site_name', config('app.name', 'AppName')) }}</span>
            </a>
            <div class="search-container d-none d-md-block">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search candidates, jobs...">
                </div>
            </div>
        </div>

        <!-- Right Side: Theme Toggle, Notifications, and Profile -->
        <div class="d-flex align-items-center">
            <!-- Theme Toggle -->
            <div class="d-flex align-items-center me-3">
                <i class="bi bi-sun me-1"></i>
                <label class="theme-toggle">
                    <input type="checkbox" id="themeToggle">
                    <span class="theme-toggle-slider"></span>
                    <span class="theme-icon sun"><i class="bi bi-sun"></i></span>
                    <span class="theme-icon moon"><i class="bi bi-moon"></i></span>
                </label>
                <i class="bi bi-moon ms-1"></i>
            </div>

            <!-- Notifications -->
            <div class="dropdown me-3 position-relative">
                <a class="position-relative" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="notification-badge">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 300px;">
                    <div class="p-3 border-bottom">
                        <h6 class="mb-0">Notifications</h6>
                    </div>
                    <div class="list-group" style="max-height: 300px; overflow-y: auto;">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="bi bi-person-check text-success"></i>
                                </div>
                                <div>
                                    <p class="mb-1">John Doe applied for Senior Developer position</p>
                                    <small class="text-muted">10 minutes ago</small>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="bi bi-calendar-event text-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-1">Interview with Jane Smith scheduled</p>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="bi bi-people text-warning"></i>
                                </div>
                                <div>
                                    <p class="mb-1">New candidate matches for UX Designer role</p>
                                    <small class="text-muted">5 hours ago</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 text-center border-top">
                        <a href="#" class="text-decoration-none">View All</a>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            <?php $user = Auth::user(); ?>
            @if (in_array(optional($user)->role, ['recruiter', 'recruiter_assistant']))
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex align-items-center text-decoration-none" href="#"
                        role="button" data-bs-toggle="dropdown">
                        @php
                            $user = Auth::user();
                            $avatarUrl =
                                'https://ui-avatars.com/api/?name=' .
                                urlencode($user->name ?? 'User') .
                                '&background=6c757d&color=fff&size=40';
                        @endphp

                        <img src="{{ $avatarUrl }}" alt="{{ $user->name ?? 'User' }}" class="user-avatar me-2">

                        <div class="d-none d-md-block">
                            <span class="fw-medium"> {{ $user->name ?? 'Name Available' }}</span>
                            <small class="d-block" style="color: var(--text-secondary);">
                                {{ ucfirst($user->role) ?? 'Name Available' }}</small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> My Profile</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('recruiter.account.settings') }}"><i
                                    class="bi bi-gear me-2"></i> Settings</a></li>
                        @if (hasAccess('manage_team'))
                            <li>
                                <a class="dropdown-item" href="{{ route('recruiter.subuser.index') }}"><i
                                        class="bi bi-people me-2"></i> Manage Team
                                </a>
                            </li>
                        @else
                            <li>
                                <a class="dropdown-item text-muted"><i class="bi bi-people me-2"></i> Manage Team
                                </a>
                            </li>
                        @endif
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        </li>
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit"><i
                                        class="bi bi-box-arrow-right me-2"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <li><a class="nav-item" href="{{ route('recruiter.login') }}"><i class="bi bi-person me-2"></i>Sign
                        In</a>
                </li>
            @endif
        </div>
    </div>
</nav>

<!-- Desktop Sidebar -->
<div class="sidebar">
    <div class="brand d-flex align-items-center">
        <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('storage/defaults/logo.png') }}"
            width="40" height="40" class="rounded me-2">
        <span class="brand-text fw-bold">{{ ucfirst(Auth::user()->role) }} Dashboard</span>
    </div>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" id="toggleCollapse">
                <i class="bi bi-arrow-left-circle"></i>
                <span class="">Collapse</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('recruiter_dashboard') ? 'active' : '' }}"
                href="{{ route('recruiter_dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        @if (hasAccess('create_job'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('create_job_view') ? 'active' : '' }}"
                    href="{{ route('create_job_view') }}">
                    <i class="bi bi-briefcase"></i>
                    <span>Create Job</span>
                    {{-- <span class="badge bg-primary ms-2">5</span> --}}
                </a>
            </li>
        @endif

        @if (hasAccess('view_applied_users'))
            <li class="nav-item ">
                <a class="nav-link {{ request()->routeIs('job.user') ? 'active' : '' }}"
                    href="{{ route('job.user') }}">
                    <i class="bi bi-people"></i>
                    <span>Applied Users</span>
                </a>
            </li>
        @endif

        @if (hasAccess('manage_all_job'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('recruiter.all_jobs') ? 'active' : '' }}"
                    href="{{ route('recruiter.all_jobs') }}">
                    <i class="bi bi-calendar-event"></i>
                    <span>All jobs</span>
                    {{-- <span class="badge bg-danger ms-2">2</span> --}}
                </a>
            </li>
        @endif

        @if (hasAccess('manage_applications'))
            <li class="nav-item ">
                <a class="nav-link {{ request()->routeIs('job_application.index') ? 'active' : '' }}"
                    href="{{ route('job_application.index') }}">
                    <i class="bi bi-envelope"></i>
                    <span>Applications</span>
                    {{-- <span class="badge bg-success ms-2">5</span> --}}
                </a>
            </li>
        @endif
        @if (hasAccess('manage_applications'))
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('recruiter.interview.index') ? 'active' : '' }}"
                href="{{ route('recruiter.interview.index') }}">
                <i class="bi bi-bar-chart"></i>
                <span>Interviews</span>
            </a>
        </li>
        @endif

        @if (hasAccess('manage_team'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('recruiter.subuser.*') ? 'active' : '' }}"
                    href="{{ route('recruiter.subuser.index') }}">
                    <i class="bi bi-gear"></i>
                    <span>Manage Team</span>
                </a>
            </li>
        @endif
        @if (hasAccess('manage_blog'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('blog.index') ? 'active' : '' }}"
                    href="{{ route('blog.index') }}">
                    <i class="bi bi-gear"></i>
                    <span>Manage Blog</span>
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a href="#" class="nav-link text-danger"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
        </li>

    </ul>
</div>

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header">
        <div class="d-flex align-items-center">
            <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('storage/defaults/logo.png') }}"
                width="40" height="40" class="rounded me-2">
            <h5 class="offcanvas-title">{{ ucfirst(Auth::user()->role) }} Dashboard</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0" style="background: #f7f9fa;">
        <ul class="nav flex-column py-2">
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('recruiter_dashboard') ? 'active' : '' }}"
                    href="{{ route('recruiter_dashboard') }}">
                    <span class="sidebar-icon bg-primary text-white me-2"><i class="bi bi-speedometer2"></i></span>
                    <span>Dashboard</span>
                </a>
            </li>
            @if (hasAccess('create_job'))
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('create_job_view') ? 'active' : '' }}"
                        href="{{ route('create_job_view') }}">
                        <span class="sidebar-icon bg-success text-white me-2"><i class="bi bi-briefcase"></i></span>
                        <span>Create Job</span>
                    </a>
                </li>
            @endif
            @if (hasAccess('view_applied_users'))
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('job.user') ? 'active' : '' }}"
                        href="{{ route('job.user') }}">
                        <span class="sidebar-icon bg-info text-white me-2"><i class="bi bi-people"></i></span>
                        <span>Applied Users</span>
                    </a>
                </li>
            @endif
            @if (hasAccess('manage_all_job'))
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('recruiter.all_jobs') ? 'active' : '' }}"
                        href="{{ route('recruiter.all_jobs') }}">
                        <span class="sidebar-icon bg-warning text-white me-2"><i
                                class="bi bi-calendar-event"></i></span>
                        <span>All Jobs</span>
                    </a>
                </li>
            @endif
            @if (hasAccess('manage_applications'))
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('job_application.index') ? 'active' : '' }}"
                        href="{{ route('job_application.index') }}">
                        <span class="sidebar-icon bg-secondary text-white me-2"><i class="bi bi-envelope"></i></span>
                        <span>Applications</span>
                    </a>
                </li>
            @endif

            </li>
            @if (hasAccess('manage_interview'))
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('recruiter.interview.index') ? 'active' : '' }}"
                    href="{{ route('recruiter.interview.index') }}">
                    <span class="sidebar-icon bg-primary text-white me-2"><i class="bi bi-calendar2-event"></i></span>
                    <span>Interviews</span>
                </a>
            </li>
            @endif

            @if (hasAccess('manage_team'))
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('recruiter.subuser.index') ? 'active' : '' }}"
                        href="{{ route('recruiter.subuser.index') }}">
                        <span class="sidebar-icon bg-info text-white me-2"><i class="bi bi-people-fill"></i></span>
                        <span>Manage Team</span>
                    </a>
                </li>
            @endif
            @if (hasAccess('manage_blog'))
                <li class="nav-item mb-1">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('blog.index') ? 'active' : '' }}"
                        href="{{ route('blog.index') }}">
                        <span class="sidebar-icon bg-warning text-white me-2"><i
                                class="bi bi-journal-text"></i></span>
                        <span>Manage Blog</span>
                    </a>
                </li>
            @endif

            <li class="nav-item mt-4 mb-2">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-flex">
                    @csrf
                    <button class="nav-link d-flex align-items-center text-danger" type="submit"
                        style="width:100%;">
                        <span class="sidebar-icon bg-danger text-white me-2"><i
                                class="bi bi-box-arrow-right"></i></span>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
