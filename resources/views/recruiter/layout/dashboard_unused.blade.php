<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ setting('site_name', config('app.name', 'AppName')) }} | Recruiter Dashboard</title>
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('storage/defaults/favicon.png') }}">

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('storage/defaults/favicon.png') }}">
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Blog Editor --}}
    <!-- TinyMCE Editor -->
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#tinymce-editor',
            height: 400,
            menubar: false,
            plugins: 'lists link image preview code',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image | preview code',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });

        function previewFeaturedImage(event) {
            const input = event.target;
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('image-preview-container');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                container.style.display = 'none';
            }
        }
    </script>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8f9fa;
        }

        .sidebar {
            background: #23272b;
            color: #fff;
            min-height: 100vh;
            width: 220px;
            transition: width 0.3s;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1050;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar .menu-item {
            cursor: pointer;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            font-size: 1.05rem;
            transition: background 0.12s, color 0.12s;
            white-space: nowrap;
        }

        .sidebar .menu-item i {
            margin-right: 12px;
            width: 22px;
            text-align: center;
            font-size: 1.18rem;
        }

        .sidebar .menu-item:hover,
        .sidebar .menu-item.active {
            background: #32383e;
            color: #f8f9fa !important;
        }

        .sidebar.collapsed .menu-text {
            display: none !important;
        }

        .sidebar .submenu {
            display: none;
            background: #292e33;
            padding-left: 32px;
        }

        .sidebar .submenu.show {
            display: block;
        }

        .header {
            background: #fff;
            border-bottom: 1px solid #e3e6ea;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-height: 74px;
            box-shadow: 0 2px 8px rgba(30, 42, 62, 0.04);
            position: sticky;
            top: 0;
            z-index: 1000;
            margin-left: 220px;
            transition: margin-left 0.3s;
        }

        .sidebar.collapsed~.main-content .header {
            margin-left: 60px;
        }

        .content-area {
            padding: 28px 32px 32px 32px;
            margin-left: 220px;
            transition: margin-left 0.3s;
            min-height: 80vh;
        }

        .sidebar.collapsed~.main-content .content-area {
            margin-left: 60px;
        }

        .dropdown-menu {
            min-width: 230px;
            border-radius: .65rem;
            box-shadow: 0 4px 18px rgba(40, 55, 85, 0.10);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                left: -220px;
                width: 220px;
                position: fixed;
                z-index: 2000;
            }

            .sidebar.show {
                left: 0;
            }

            .sidebar.collapsed {
                width: 60px;
            }

            .header,
            .content-area {
                margin-left: 0 !important;
            }

            .sidebar.collapsed~.main-content .header,
            .sidebar.collapsed~.main-content .content-area {
                margin-left: 0 !important;
            }

            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.4);
                z-index: 1999;
            }

            .sidebar.show~.sidebar-backdrop {
                display: block;
            }
        }

        .sidebar .menu-toggle-btn {
            display: none;
        }

        @media (max-width: 991.98px) {
            .sidebar .menu-toggle-btn {
                display: flex;
                justify-content: flex-end;
                padding: 8px 12px;
            }
        }
    </style>
    @stack('create_job_styles')
</head>

<body>
    @include('components.alert')

    <!-- Sidebar -->
    <div class="sidebar" id="desktopSidebar">
        <div class="menu-toggle-btn d-lg-none" onclick="toggleSidebarMobile()">
            <i class="fas fa-times text-white" style="font-size: 1.7rem;"></i>
        </div>
        <div class="menu-item" onclick="toggleDesktopSidebar()">
            <i class="fas fa-bars"></i>
            <span class="menu-text">Menu</span>
        </div>
        <div class="menu-item {{ request()->routeIs('recruiter_dashboard') ? 'active' : '' }}"
            onclick="window.location.href='{{ route('recruiter_dashboard') }}'">
            <i class="fas fa-tachometer-alt"></i>
            <span class="menu-text">Dashboard</span>
        </div>
        <div class="menu-item" onclick="toggleMenu('createDesktop')">
            <i class="fas fa-plus-circle"></i>
            <span class="menu-text">Create</span>
            <i class="fas fa-chevron-down ms-auto"></i>
        </div>
        <div class="submenu" id="createDesktop">
            @if (hasAccess('create_job'))
                <div class="menu-item {{ request()->routeIs('create_job_view') ? 'active' : '' }}"
                    onclick="window.location.href='{{ route('create_job_view') }}'">
                    <i class="fas fa-briefcase"></i><span class="menu-text">Job</span>
                </div>
            @endif
            @if (hasAccess('applied_user'))
                <div class="menu-item {{ request()->routeIs('job.user') ? 'active' : '' }}"
                    onclick="window.location.href='{{ route('job.user') }}'"><i class="fas fa-user"></i> <span
                        class="menu-text">Applied User</span></div>
            @endif
        </div>
        <div class="menu-item" onclick="toggleMenu('jobsDesktop')">
            <i class="fas fa-briefcase"></i>
            <span class="menu-text">Jobs</span>
            <i class="fas fa-chevron-down ms-auto"></i>
        </div>
        <div class="submenu" id="jobsDesktop">
            @if (hasAccess('manage_all_job'))
                <div class="menu-item {{ request()->routeIs('recruiter.all_jobs') ? 'active' : '' }}"
                    onclick="window.location.href='{{ route('recruiter.all_jobs') }}'">
                    <i class="fas fa-list"></i><span class="menu-text">All Jobs</span>
                </div>
            @endif
            @if (hasAccess('manage_applications'))
                <div class="menu-item {{ request()->routeIs('job_application.index') ? 'active' : '' }}"
                    onclick="window.location.href='{{ route('job_application.index') }}'">
                    <i class="fas fa-user-check"></i><span class="menu-text">Applications</span>
                </div>
            @endif
        </div>
        <div class="menu-item {{ request()->routeIs('recruiter_calls') ? 'active' : '' }}"
            onclick="window.location.href='{{ route('recruiter_calls') }}'">
            <i class="fas fa-phone-alt"></i>
            <span class="menu-text">Calls</span>
        </div>
        {{-- <div class="menu-item">
                <i class="fas fa-magic"></i>
                <span class="menu-text">Smart Sourcing</span>
            </div> --}}
        <div class="menu-item {{ request()->routeIs('recruiter_candidates') ? 'active' : '' }}"
            onclick="window.location.href='{{ route('recruiter_candidates') }}'">
            <i class="fas fa-users"></i>
            <span class="menu-text">Candidates</span>
        </div>
        {{-- <div class="menu-item" onclick="toggleMenu('interviewsDesktop')">
            <i class="fas fa-comments"></i>
            <span class="menu-text">Interviews</span>
            <i class="fas fa-chevron-down ms-auto"></i>
        </div> --}}
        <div class="submenu" id="interviewsDesktop">
            <div class="menu-item"><i class="fas fa-calendar-check"></i> <span class="menu-text">Availability</span>
            </div>
        </div>
        {{-- <div class="menu-item" onclick="toggleMenu('analyticsDesktop')">
            <i class="fas fa-chart-bar"></i>
            <span class="menu-text">Analytics</span>
            <i class="fas fa-chevron-down ms-auto"></i>
        </div> --}}
        <div class="submenu" id="analyticsDesktop">
            <div class="menu-item"><i class="fas fa-chart-pie"></i> <span class="menu-text">Overview</span></div>
            <div class="menu-item"><i class="fas fa-bullhorn"></i> <span class="menu-text">Campaigns</span></div>
            <div class="menu-item"><i class="fas fa-magic"></i> <span class="menu-text">Smart Sourcing</span></div>
        </div>
        {{-- <div class="menu-item" onclick="toggleMenu('toolsDesktop')">
            <i class="fas fa-tools"></i>
            <span class="menu-text">Tools</span>
            <i class="fas fa-chevron-down ms-auto"></i>
        </div> --}}
        <div class="submenu" id="toolsDesktop">
            <div class="menu-item"><i class="fas fa-info-circle"></i> <span class="menu-text">Overview</span></div>
            <div class="menu-item"><i class="fas fa-plug"></i> <span class="menu-text">ATS Integrations</span></div>
        </div>
        @if (hasAccess('manage_team'))
            <div class="menu-item" onclick="window.location.href='{{ route('recruiter.subuser.index') }}'">
                <i class="fas fa-user-shield"></i>
                <span class="menu-text">Manage Team</span>
            </div>
        @endif
        @if (hasAccess('blog_access'))
            <div class="menu-item" onclick="window.location.href='{{ route('blog.index') }}'">
                <i class="fas fa-book-open"></i>
                <span class="menu-text">Manage Blog</span>
            </div>
        @endif

        <div class="menu-item" onclick="window.location.href='{{ route('logout') }}'">
            <i class="fas fa-sign-out-alt"></i>
            <span class="menu-text">Logout</span>
        </div>
    </div>
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeSidebarMobile()"></div>
    <!-- Main Content -->
    <div class="main-content">
        <header class="header">
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-dark d-lg-none me-2 p-0" onclick="openSidebarMobile()"
                    style="font-size: 1.6rem;">
                    <i class="fas fa-bars"></i>
                </button>
                <img src="https://ui-avatars.com/api/?name=App+Dashboard&background=343a40&color=fff&size=40"
                    alt="Logo" style="width:40px; height:40px; border-radius:8px; margin-right:16px;">
                <span class="title">App Dashboard</span>
            </div>
            <div class="d-flex align-items-center">
                <div class="top-icons d-flex align-items-center" style="gap: 20px;">
                    <div class="d-flex align-items-center" style="cursor:pointer;">
                        <span class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                            style="width:38px; height:38px;">
                            <i class="fas fa-question-circle text-primary" title="Help"></i>
                        </span>
                        <span class="ms-2 text-secondary" style="font-size: 1rem;">Help</span>
                    </div>
                    <div class="d-flex align-items-center" style="cursor:pointer;">
                        <span class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                            style="width:38px; height:38px;">
                            <i class="fas fa-bell text-warning" title="Notifications"></i>
                        </span>
                        <span class="ms-2 text-secondary" style="font-size: 1rem;">Notifications</span>
                    </div>
                    <div class="d-flex align-items-center position-relative" style="cursor:pointer;">
                        <span
                            class="rounded-circle bg-light d-flex align-items-center justify-content-center position-relative"
                            style="width:38px; height:38px;">
                            <i class="fas fa-envelope text-info" title="Messages"></i>
                            @if ($totalUnread > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="font-size: 0.62rem;">
                                    {{ $totalUnread }}
                                </span>
                            @endif
                        </span>
                        <span class="ms-2 text-secondary" style="font-size: 1rem;">Messages</span>
                    </div>
                </div>
                <?php $user = Auth::user(); ?>
                @if (in_array(optional($user)->role, ['recruiter', 'recruiter_assistant']))
                    <div class="dropdown ms-4" style="position: relative;">
                        <a href="#"
                            class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                            id="desktopUserMenu" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false" style="background: none; border: none; padding: 0;">
                            @php
                                $user = Auth::user();
                                $avatarUrl =
                                    'https://ui-avatars.com/api/?name=' .
                                    urlencode($user->name ?? 'User') .
                                    '&background=6c757d&color=fff&size=40';
                            @endphp
                            <img src="{{ $avatarUrl }}" alt="{{ $user->name ?? 'User' }}"
                                style="width:40px; height:40px; border-radius:50%;">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="desktopUserMenu">
                            <div class="px-3 py-2 fw-semibold">
                                {{ $user->name ?? 'Name Available' }}
                            </div>
                            <div class="px-3 pb-2 text-muted" style="font-size:0.96rem;">
                                {{ $user->email ?? 'Email Unavailable' }}
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item d-flex align-items-center"
                                href="{{ route('recruiter.account.settings') }}"><i class="fas fa-user-cog me-2"></i>
                                Account Settings</a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i
                                    class="fas fa-envelope text-info me-2"></i> Messages</a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i
                                    class="fas fa-bell text-warning me-2"></i> Notifications</a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i
                                    class="fas fa-file-invoice text-secondary me-2"></i> Billings & Invoices</a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i
                                    class="fas fa-undo-alt text-primary me-2"></i> Subscriptions</a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i
                                    class="fas fa-cog text-dark me-2"></i> Employer Settings</a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i
                                    class="fas fa-building text-success me-2"></i> Company Page</a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i
                                    class="fas fa-users text-info me-2"></i> Users</a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i
                                    class="fas fa-toolbox text-secondary me-2"></i> ATS Integrations</a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i
                                    class="fas fa-headset text-primary me-2"></i> Contact Us</a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><i
                                    class="fas fa-external-link-alt me-2"></i> Visit App Dashboard for Jobseekers</a>
                            <a class="dropdown-item d-flex align-items-center text-danger" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Sign out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <li class="nav-item">
                        <a href="{{ route('recruiter.login') }}" class="btn btn-outline-primary btn-sm ms-2"
                            role="button" aria-label="Sign In">Sign In</a>
                    </li>
                @endif
            </div>
        </header>
        <main class="content-area">
            @yield('content')
        </main>
    </div>
    @yield('footer')
    <!-- JavaScript -->
    <script>
        // DESKTOP
        function toggleDesktopSidebar() {
            const sidebar = document.getElementById('desktopSidebar');
            sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed');
        }

        function toggleMenu(id) {
            const all = document.querySelectorAll('#desktopSidebar .submenu');
            all.forEach(menu => {
                if (menu.id !== id) menu.classList.remove('show');
            });
            const target = document.getElementById(id);
            if (target) target.classList.toggle('show');
        }

        // RESPONSIVE - mobile sidebar
        function openSidebarMobile() {
            document.getElementById('desktopSidebar').classList.add('show');
            document.getElementById('sidebarBackdrop').style.display = 'block';
        }

        function closeSidebarMobile() {
            document.getElementById('desktopSidebar').classList.remove('show');
            document.getElementById('sidebarBackdrop').style.display = 'none';
        }
        // Close sidebar on backdrop click (mobile)
        document.getElementById('sidebarBackdrop').addEventListener('click', closeSidebarMobile);

        // On resize, ensure sidebar is hidden on mobile if width increases
        window.addEventListener('resize', function() {
            if (window.innerWidth > 991) {
                document.getElementById('desktopSidebar').classList.remove('show');
                document.getElementById('sidebarBackdrop').style.display = 'none';
            }
        });
    </script>
    @stack('create_job_scripts')
    @stack('job_edit_scripts')
    @stack('recruiter_msg_scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>