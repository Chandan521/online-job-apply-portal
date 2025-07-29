<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ setting('site_name', config('app.name')) }}@hasSection('title')
            | @yield('title')
        @endif
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('storage/defaults/favicon.png') }}">

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('storage/defaults/favicon.png') }}">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>

    <script>
        tinymce.init({
            selector: '#editor',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media table visualblocks wordcount',
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image media | code preview',
            height: 400,
            menubar: false,
            image_caption: true,
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });
    </script>
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --border-color: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --card-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --card-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--light-color);
            color: var(--text-primary);
            line-height: 1.6;
        }

        .layout-wrapper {
            display: grid;
            grid-template-columns: 280px 1fr;
            grid-template-rows: 70px 1fr;
            height: 100vh;
            grid-template-areas:
                "sidebar header"
                "sidebar main";
            transition: grid-template-columns 0.3s ease;
        }

        .layout-wrapper.sidebar-collapsed {
            grid-template-columns: 80px 1fr;
        }

        /* Sidebar Styles */
        .sidebar {
            grid-area: sidebar;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #0c1220 100%);
            border-right: 1px solid #334155;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .sidebar-header {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid #334155;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            text-decoration: none;
        }

        .brand-icon {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .brand-text {
            font-size: 1.25rem;
            font-weight: 700;
            transition: opacity 0.3s ease;
        }

        .sidebar-menu {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 0.875rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            border: none;
            border-radius: 0;
            position: relative;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: white;
            transform: translateX(4px);
        }

        .sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: white;
            position: relative;
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: white;
        }

        .sidebar .nav-icon {
            font-size: 1.125rem;
            min-width: 20px;
            text-align: center;
        }

        .sidebar .nav-text {
            transition: opacity 0.3s ease;
        }

        .collapse-icon {
            font-size: 0.875rem;
            transition: transform 0.3s ease;
        }

        .nav-link[aria-expanded="true"] .collapse-icon {
            transform: rotate(180deg);
        }

        .submenu {
            background-color: rgba(0, 0, 0, 0.2);
        }

        .submenu .nav-link {
            padding: 0.75rem 1.25rem 0.75rem 3rem;
            font-size: 0.875rem;
            position: relative;
        }

        .submenu-dot {
            width: 6px;
            height: 6px;
            background-color: var(--text-muted);
            border-radius: 50%;
            margin-right: 0.75rem;
        }

        .submenu .nav-link:hover .submenu-dot {
            background-color: var(--primary-color);
        }

        /* Collapsed Sidebar */
        .layout-wrapper.sidebar-collapsed .brand-text,
        .layout-wrapper.sidebar-collapsed .nav-text,
        .layout-wrapper.sidebar-collapsed .collapse-icon {
            opacity: 0;
            pointer-events: none;
            width: 0;
        }

        .layout-wrapper.sidebar-collapsed .sidebar .nav-link {
            justify-content: center;
        }

        .layout-wrapper.sidebar-collapsed .submenu {
            display: none;
        }

        /* Top Navbar */
        .top-navbar {
            grid-area: header;
            background: white;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            box-shadow: var(--card-shadow);
            z-index: 1000;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-ghost {
            background: none;
            border: none;
            color: var(--text-secondary);
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-ghost:hover {
            background-color: var(--light-color);
            color: var(--text-primary);
        }

        .sidebar-toggle {
            font-size: 1.25rem;
        }

        .breadcrumb-nav .current-page {
            font-weight: 600;
            color: var(--text-primary);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .notification-btn {
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: relative;
        }

        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            color: var(--text-primary);
            transition: background-color 0.2s ease;
        }

        .profile-trigger:hover {
            background-color: var(--light-color);
            color: var(--text-primary);
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid var(--border-color);
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .profile-name {
            font-weight: 600;
            font-size: 0.875rem;
            line-height: 1.2;
        }

        .profile-role {
            font-size: 0.75rem;
            color: var(--text-muted);
            line-height: 1.2;
        }

        /* Dropdown Menus */
        .dropdown-menu {
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow-lg);
            border-radius: 0.75rem;
            padding: 0.5rem;
            min-width: 220px;
        }

        .dropdown-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 0.5rem;
        }

        .dropdown-header h6 {
            margin: 0;
            font-weight: 600;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s ease;
            display: flex;
            align-items: center;
        }

        .dropdown-item:hover {
            background-color: var(--light-color);
        }

        .dropdown-footer {
            padding: 0.5rem;
            border-top: 1px solid var(--border-color);
            margin-top: 0.5rem;
        }

        /* Notification Dropdown */
        .notification-dropdown {
            width: 320px;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s ease;
        }

        .notification-item:hover {
            background-color: var(--light-color);
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.875rem;
        }

        .notification-content p {
            margin: 0;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .notification-content small {
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        /* Profile Menu */
        .profile-menu {
            width: 280px;
        }

        .profile-menu .dropdown-header {
            padding: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Main Content */
        .main-content {
            grid-area: main;
            overflow-y: auto;
            padding: 2rem;
            background-color: var(--light-color);
        }

        .content-page {
            display: none;
        }

        .content-page.active {
            display: block;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1.125rem;
            margin: 0;
        }

        .page-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-lg);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stats-icon.bg-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }

        .stats-icon.bg-success {
            background: linear-gradient(135deg, var(--success-color), #059669);
        }

        .stats-icon.bg-warning {
            background: linear-gradient(135deg, var(--warning-color), #d97706);
        }

        .stats-icon.bg-info {
            background: linear-gradient(135deg, var(--info-color), #0891b2);
        }

        .stats-content {
            flex: 1;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .stats-label {
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stats-change {
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stats-change.positive {
            color: var(--success-color);
        }

        .stats-change.negative {
            color: var(--danger-color);
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fafbfc;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
        }

        .card-body {
            padding: 1.5rem;
            color: var(--text-primary);
        }

        .card-footer {
            padding: 1rem 1.5rem;
            background-color: #fafbfc;
            border-top: 1px solid var(--border-color);
        }

        /* Chart Placeholder */
        .chart-placeholder {
            height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            background-color: #fafbfc;
            border-radius: 0.5rem;
            border: 2px dashed var(--border-color);
        }

        .chart-placeholder i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* Application Items */
        .application-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s ease;
        }

        .application-item:hover {
            background-color: #fafbfc;
        }

        .application-item:last-child {
            border-bottom: none;
        }

        .application-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid var(--border-color);
        }

        .application-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .application-info {
            flex: 1;
        }

        .application-info h6 {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .application-info p {
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .application-info small {
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        .application-status {
            display: flex;
            align-items: center;
        }

        /* Badges */
        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
        }

        /* Buttons */
        .btn {
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), #1e40af);
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Form Controls */
        .form-select {
            border-radius: 0.5rem;
            border-color: var(--border-color);
        }

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .layout-wrapper {
                grid-template-columns: 260px 1fr;
            }

            .layout-wrapper.sidebar-collapsed {
                grid-template-columns: 70px 1fr;
            }
        }

        @media (max-width: 992px) {
            .layout-wrapper {
                grid-template-columns: 1fr;
                grid-template-rows: 70px 1fr;
                grid-template-areas:
                    "header"
                    "main";
            }

            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                width: 280px;
                z-index: 1050;
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                padding: 1rem;
            }

            .page-header {
                margin-bottom: 1.5rem;
            }

            .page-title {
                font-size: 1.75rem;
            }

            .stats-card {
                padding: 1.25rem;
            }

            .stats-number {
                font-size: 1.75rem;
            }

            .profile-info {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .top-navbar {
                padding: 0 1rem;
            }

            .navbar-actions {
                gap: 0.25rem;
            }

            .notification-dropdown {
                width: 280px;
            }

            .profile-menu {
                width: 240px;
            }

            .stats-card {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }

            .stats-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            .application-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .application-status {
                align-self: flex-end;
            }
        }

        /* Dark mode support (optional) */
        @media (prefers-color-scheme: dark) {
            :root {
                --light-color: #0f172a;
                --text-primary: #f1f5f9;
                --text-secondary: #cbd5e1;
                --text-muted: #64748b;
                --border-color: #334155;
            }

            .card,
            .stats-card,
            .top-navbar {
                background-color: #1e293b;
                border-color: var(--border-color);
            }

            .card-header,
            .card-footer {
                background-color: #0f172a;
            }

            .chart-placeholder {
                background-color: #0f172a;
            }

            .application-item:hover {
                background-color: #0f172a;
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }

        /* Loading Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .content-page.active {
            animation: fadeIn 0.3s ease-out;
        }

        /* Application Status Badges (Reusing established colors) */
        .badge-status-submitted {
            background-color: #6c757d;
        }

        /* Gray */
        .badge-status-under_review {
            background-color: #ffc107;
            color: #343a40;
        }

        /* Warning - Yellow */
        .badge-status-shortlisted {
            background-color: #0dcaf0;
        }

        /* Info - Light Blue */
        .badge-status-interview {
            background-color: #0d6efd;
        }

        /* Primary - Blue */
        .badge-status-selected {
            background-color: #198754;
        }

        /* Success - Green */
        .badge-status-rejected {
            background-color: #dc3545;
        }

        /* Danger - Red */
        .badge-status-hired {
            background-color: #20c997;
        }

        /* Teal */
        .badge-status-withdrawn {
            background-color: #6f42c1;
        }

        /* Purple */

        .badge {
            padding: 0.4em 0.7em;
            /* Consistent badge padding */
            font-size: 0.85em;
            /* Slightly larger for better readability */
            font-weight: 600;
            /* Bolder text */
            text-transform: uppercase;
            border-radius: 0.25rem;
            /* Slightly rounded for sharper look */
        }

        .badge.rounded-pill {
            /* Keep rounded-pill if preferred for some badges */
            border-radius: 50rem !important;
        }
    </style>
    @stack('dashboard-styles')
    @stack('create_job_styles')
    @stack('view_job_styles')
    @stack('job_application_styles')
    @stack('view_user_style')
    @stack('view_recruiter_styles')
</head>

<body>
    <div class="layout-wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <div class="brand-logo">
                    <i class="bi bi-briefcase-fill brand-icon"></i>
                    <span class="brand-text">{{ setting('site_name', config('app.name')) }}</span>
                </div>
            </div>

            <div class="sidebar-menu">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2-fill nav-icon"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.jobs.index') }}"
                            class="nav-link {{ request()->routeIs('admin.jobs.index') ? 'active' : '' }}">
                            <i class="bi bi-briefcase nav-icon"></i>
                            <span class="nav-text">Job Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.applications.index') }}"
                            class="nav-link {{ request()->routeIs('admin.applications.index') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-person nav-icon"></i>
                            <span class="nav-text">Job Applications</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.index') }}"
                            class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="bi bi-flag nav-icon"></i>
                            <span class="nav-text">User Reports</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.static_pages.index') }}"
                            class="nav-link {{ request()->routeIs('admin.static_pages.*') ? 'active' : '' }}">
                            <i class="bi bi-flag nav-icon"></i>
                            <span class="nav-text">Static Pages</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.blog.index') }}"
                            class="nav-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                            <i class="bi bi-flag nav-icon"></i>
                            <span class="nav-text">Manage Blogs</span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ route('admin.banned_ips.index') }}"
                            class="nav-link {{ request()->routeIs('admin.banned_ips.*') ? 'active' : '' }}">
                            <i class="bi bi-shield-lock nav-icon"></i>
                            <span class="nav-text">IP Ban</span>
                        </a>
                    </li>

                    {{-- <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#applicationsMenu" role="button"
                            aria-expanded="false">
                            <i class="bi bi-file-earmark-person nav-icon"></i>
                            <span class="nav-text">Applications</span>
                            <i class="bi bi-chevron-down ms-auto collapse-icon"></i>
                        </a>
                        <div class="collapse" id="applicationsMenu">
                            <ul class="nav flex-column submenu">
                                <li class="nav-item">
                                    <a href="" class="nav-link" data-page="new-applications">
                                        <span class="submenu-dot"></span>
                                        New Applications
                                        <span class="badge bg-primary ms-auto">12</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-page="shortlisted">
                                        <span class="submenu-dot"></span>
                                        Shortlisted
                                        <span class="badge bg-success ms-auto">8</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-page="interviews">
                                        <span class="submenu-dot"></span>
                                        Interviews
                                        <span class="badge bg-warning ms-auto">5</span>
                                    </a>
                                </li>
                                
                                
                                
                                
                            </ul>
                        </div>
                    </li> --}}

                    <li class="nav-item">
                        <a href="{{ route('admin.companies') }}"
                            class="nav-link {{ request()->routeIs('admin.companies') ? 'active' : '' }}">
                            <i class="bi bi-building nav-icon"></i>
                            <span class="nav-text">Companies</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('users.index') }}"
                            class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill nav-icon"></i>
                            <span class="nav-text">User Management</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.analytics') }}"
                            class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                            <i class="bi bi-graph-up nav-icon"></i>
                            <span class="nav-text">Analytics</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.settings') }}"
                            class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                            <i class="bi bi-gear nav-icon"></i>
                            <span class="nav-text">Settings</span>
                        </a>
                    </li>

                </ul>
            </div>
        </nav>
        @include('components.alert')
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="navbar-left">
                <button class="btn btn-ghost sidebar-toggle" id="toggleSidebar">
                    <i class="bi bi-list"></i>
                </button>
                <div class="breadcrumb-nav">
                    <span class="current-page">@yield('page-title')</span>
                </div>
            </div>

            <div class="navbar-right">
                <div class="navbar-actions">
                    <button class="btn btn-ghost notification-btn" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                        <div class="dropdown-header">
                            <h6>Notifications</h6>
                            <span class="badge bg-primary">3 New</span>
                        </div>
                        <div class="notification-item">
                            <div class="notification-icon bg-primary">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <div class="notification-content">
                                <p>New application received</p>
                                <small>2 minutes ago</small>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-icon bg-success">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="notification-content">
                                <p>Interview scheduled</p>
                                <small>1 hour ago</small>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-icon bg-warning">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="notification-content">
                                <p>Job posting expires soon</p>
                                <small>3 hours ago</small>
                            </div>
                        </div>
                        <div class="dropdown-footer">
                            <a href="#" class="btn btn-sm btn-outline-primary w-100">View All</a>
                        </div>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="profile-dropdown dropdown">
                    <a class="profile-trigger" href="#" data-bs-toggle="dropdown">
                        <div class="avatar">
                            @php
                                $user = Auth::user();
                                $avatar = $user->profile_photo
                                    ? asset('storage/' . $user->profile_photo)
                                    : 'https://ui-avatars.com/api/?name=' .
                                        urlencode($user->name) .
                                        '&size=40&background=random';
                            @endphp
                            <img src="{{ $avatar }}" alt="{{ $user->name }}" class="avatar-img">
                        </div>

                        <div class="profile-info">
                            <span class="profile-name">{{ ucwords(Auth::user()->name) }}</span>
                            <span class="profile-role">{{ ucfirst(Auth::user()->role) }}</span>
                        </div>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end profile-menu">
                        <li class="dropdown-header">
                            <div class="user-info">
                                <div class="avatar">
                                    <img src="{{ $avatar }}" alt="{{ $user->name }}" class="avatar-img">
                                </div>

                                <div>
                                    <div class="fw-semibold">{{ ucwords(Auth::user()->name) }}</div>
                                    <div class="text-muted small">{{ ucwords(Auth::user()->email) }}</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('admin.profile.show') }}"><i
                                    class="bi bi-person me-2"></i>My Profile</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('admin.profile.index') }}"><i
                                    class="bi bi-gear me-2"></i>Account
                                Settings</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.help') }}"><i
                                    class="bi bi-question-circle me-2"></i>Help
                                Center</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger" href="#"><i
                                        class="bi bi-box-arrow-right me-2"></i>Sign
                                    Out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    @stack('user_permission_script')
    @stack('scripts')
    <script>
        class AdminDashboard {
            constructor() {
                this.sidebar = document.getElementById('sidebar');
                this.toggleBtn = document.getElementById('toggleSidebar');
                this.layoutWrapper = document.querySelector('.layout-wrapper');
                this.navLinks = document.querySelectorAll('.nav-link[data-page]');
                this.contentPages = document.querySelectorAll('.content-page');
                this.currentPageSpan = document.querySelector('.current-page');

                this.init();
            }

            init() {
                this.bindEvents();
                this.initializeTooltips();
                this.updateDateTime();
                this.setActiveNavigation();
            }

            bindEvents() {
                // Sidebar toggle
                this.toggleBtn.addEventListener('click', () => {
                    this.toggleSidebar();
                });

                // Close sidebar on mobile when clicking outside
                document.addEventListener('click', (e) => {
                    if (window.innerWidth <= 992) {
                        if (!this.sidebar.contains(e.target) && !this.toggleBtn.contains(e.target)) {
                            this.sidebar.classList.remove('show');
                        }
                    }
                });

                // Handle window resize
                window.addEventListener('resize', () => {
                    this.handleResize();
                });

                // Dropdown toggles
                this.initializeDropdowns();
            }

            toggleSidebar() {
                if (window.innerWidth <= 992) {
                    // Mobile behavior
                    this.sidebar.classList.toggle('show');
                } else {
                    // Desktop behavior
                    this.layoutWrapper.classList.toggle('sidebar-collapsed');
                }
            }

            setActiveNavigation() {
                // This is now handled by Blade directives
            }

            handleResize() {
                if (window.innerWidth > 992) {
                    this.sidebar.classList.remove('show');
                }
            }

            initializeDropdowns() {
                // Handle collapse icons rotation
                const collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
                collapseElements.forEach(element => {
                    element.addEventListener('click', function() {
                        const icon = this.querySelector('.collapse-icon');
                        if (icon) {
                            setTimeout(() => {
                                const isExpanded = this.getAttribute('aria-expanded') ===
                                    'true';
                                icon.style.transform = isExpanded ? 'rotate(180deg)' :
                                    'rotate(0deg)';
                            }, 10);
                        }
                    });
                });
            }

            initializeTooltips() {
                // Initialize Bootstrap tooltips if needed
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            updateDateTime() {
                // Update any date/time elements
                const now = new Date();
                const timeElements = document.querySelectorAll('.current-time');
                timeElements.forEach(element => {
                    element.textContent = now.toLocaleTimeString();
                });
            }
        }

        // Initialize dashboard when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const dashboard = new AdminDashboard();
            window.adminDashboard = dashboard;
        });
    </script>
    
    @stack('analytics-scripts')
    @stack('create_job_scripts')
    @stack('job_application_script')
</body>

</html>
