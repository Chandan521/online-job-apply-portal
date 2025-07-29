<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('site_name', config('app.name', 'AppName')) }} | Recruiter Dashboard</title>
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('storage/defaults/favicon.png') }}">
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('storage/defaults/favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Pdf Viewer  --}}
    <!-- PDF.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>

    {{-- Blog Editor --}}
    <!-- TinyMCE Editor -->
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>

    {{-- Graph Chat --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .sidebar-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.1em;
            height: 2.1em;
            border-radius: 50%;
            font-size: 1.1em;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04), 0 1.5px 6px rgba(0, 0, 0, 0.03);
            margin-right: 0.7em;
        }

        .nav-link.active,
        .nav-link:focus,
        .nav-link:hover {
            background: #e9f5fa !important;
            color: #1967d2 !important;
            border-left: 4px solid #1967d2;
            font-weight: bold;
            border-radius: 0 25px 25px 0;
        }

        .nav-link {
            transition: all 0.15s;
            font-size: 1.04em;
            color: #495057;
            border-left: 4px solid transparent;
            border-radius: 0 25px 25px 0;
            padding: 0.75em 1em;
            background: transparent;
        }

        .nav-link span:last-child {
            white-space: nowrap;
        }

        @media (max-width: 575.98px) {
            .nav-link {
                font-size: 1em;
                padding: 0.65em 0.7em;
            }

            .sidebar-icon {
                width: 1.8em;
                height: 1.8em;
                font-size: 1em;
            }
        }
.sidebar {
    background: #f7f9fa;
    border-right: 1px solid #e3e6ea;
    min-height: 100vh;
    padding-bottom: 2.5rem;
    transition: all 0.18s;
    z-index: 1030;
}
.sidebar-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.1em;
    height: 2.1em;
    border-radius: 50%;
    font-size: 1.13em;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04), 0 1.5px 6px rgba(0,0,0,0.03);
    margin-right: 0.7em;
}
.sidebar-nav .nav-link.active, .sidebar-nav .nav-link:focus, .sidebar-nav .nav-link:hover {
    background: #e9f5fa !important;
    color: #1967d2 !important;
    border-left: 4px solid #1967d2;
    font-weight: bold;
    border-radius: 0 25px 25px 0;
}
.sidebar-nav .nav-link {
    transition: all 0.16s;
    font-size: 1.09em;
    color: #495057;
    border-left: 4px solid transparent;
    border-radius: 0 25px 25px 0;
    padding: 0.72em 1.18em;
    background: transparent;
}
.sidebar-nav .nav-link span:last-child {
    white-space: nowrap;
}
@media (max-width: 991.98px) {
    .sidebar { min-width: 100px; max-width: 140px; }
    .sidebar .brand-text { display: none; }
    .sidebar-nav .nav-link { padding-left: 0.7em; font-size: 0.97em; }
}
@media (max-width: 575.98px) {
    .sidebar { min-width: 70px; max-width: 100px; }
    .sidebar-icon { width: 1.6em; height: 1.6em; font-size: 0.95em; }
}
        :root {
            --primary-color: #3a86ff;
            --secondary-color: #8338ec;
            --accent-color: #ff006e;
            --dark-color: #1e1f26;
            --light-color: #f8f9fa;
            --sidebar-width: 250px;
            --sidebar-collapsed: 70px;
            --card-border-radius: 12px;
            --transition-speed: 0.3s;
        }

        [data-theme="dark"] {
            --bg-primary: #121212;
            --bg-secondary: #1e1e1e;
            --bg-card: #242424;
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --border-color: #333333;
        }

        [data-theme="light"] {
            --bg-primary: #f5f7fb;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --text-primary: #1e1f26;
            --text-secondary: #5c5c5c;
            --border-color: #e0e0e0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            transition: background-color var(--transition-speed), color var(--transition-speed);
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Top Navbar Styles */
        .top-navbar {
            background: var(--bg-secondary);
            height: 70px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1030;
            border-bottom: 1px solid var(--border-color);
        }

        .top-navbar .search-container {
            max-width: 400px;
        }

        .top-navbar .search-container input {
            border-radius: 20px;
            padding: 8px 20px;
            background-color: var(--bg-primary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border-color);
        }

        /* Sidebar Styles */
        .sidebar {
            background: var(--bg-secondary);
            color: var(--text-primary);
            height: calc(100vh - 70px);
            position: fixed;
            top: 70px;
            left: 0;
            width: var(--sidebar-width);
            transition: all var(--transition-speed) ease;
            overflow-y: auto;
            z-index: 1020;
            border-right: 1px solid var(--border-color);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .collapse-text {
            display: none;
        }

        .sidebar.collapsed .brand-text {
            display: none;
        }

        .sidebar .nav-link {
            color: var(--text-secondary);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: var(--card-border-radius);
            display: flex;
            align-items: center;
            transition: all var(--transition-speed);
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(58, 134, 255, 0.1);
            color: var(--primary-color);
        }

        .sidebar .nav-link i {
            font-size: 1.2rem;
            margin-right: 15px;
            width: 24px;
            text-align: center;
        }

        .sidebar .brand {
            padding: 20px 15px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 10px;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            padding: 0 15px;
        }

        .toggle-collapse {
            background: rgba(58, 134, 255, 0.1);
            color: var(--primary-color);
            border: none;
            border-radius: var(--card-border-radius);
            width: 100%;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition-speed);
        }

        .toggle-collapse:hover {
            background: rgba(58, 134, 255, 0.2);
        }

        .sidebar.collapsed .toggle-collapse i {
            transform: rotate(180deg);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 70px;
            padding: 25px;
            transition: all var(--transition-speed) ease;
        }

        .sidebar.collapsed+.main-content {
            margin-left: var(--sidebar-collapsed);
        }

        /* Mobile Offcanvas */
        .mobile-menu-btn {
            display: none;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: block;
            }

            .brand-text {
                display: block !important;
            }

            .top-navbar .search-container {
                max-width: 250px;
            }
        }

        @media (max-width: 768px) {
            .top-navbar .search-container {
                display: none;
            }

            .main-content {
                padding: 15px;
            }
        }

        /* Dashboard Content */
        .dashboard-header {
            margin-bottom: 30px;
        }

        .stats-card {
            background: var(--bg-card);
            border-radius: var(--card-border-radius);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 25px 20px;
            margin-bottom: 20px;
            transition: transform var(--transition-speed);
            border: 1px solid var(--border-color);
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .stats-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--accent-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Theme Toggle */
        .theme-toggle {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
            margin: 0 15px;
        }

        .theme-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .theme-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .theme-toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.theme-toggle-slider {
            background-color: var(--primary-color);
        }

        input:checked+.theme-toggle-slider:before {
            transform: translateX(30px);
        }

        .theme-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: white;
        }

        .theme-icon.sun {
            left: 8px;
        }

        .theme-icon.moon {
            right: 8px;
        }

        /* Recent Activity */
        .activity-card {
            background: var(--bg-card);
            border-radius: var(--card-border-radius);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 20px;
            border: 1px solid var(--border-color);
            height: 100%;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(58, 134, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .activity-icon i {
            color: var(--primary-color);
            font-size: 18px;
        }

        .activity-content h6 {
            margin-bottom: 5px;
            font-weight: 600;
        }

        .activity-content p {
            margin-bottom: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Progress Bar */
        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: var(--bg-primary);
        }

        .progress-bar {
            background-color: var(--primary-color);
        }

        /* Quick Actions */
        .quick-action {
            background: var(--bg-card);
            border-radius: var(--card-border-radius);
            padding: 15px;
            text-align: center;
            border: 1px solid var(--border-color);
            transition: all var(--transition-speed);
        }

        .quick-action:hover {
            background: rgba(58, 134, 255, 0.1);
            border-color: var(--primary-color);
            transform: translateY(-3px);
        }

        .quick-action i {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        /* Device Mode Indicator */
        .device-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--bg-card);
            color: var(--text-primary);
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 0.8rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            z-index: 1000;
            display: flex;
            align-items: center;
        }

        .device-indicator i {
            margin-right: 8px;
            color: var(--primary-color);
        }

        /* Zoom Warning */
        .zoom-warning {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            background: #ffcc00;
            color: #333;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 0.9rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 1050;
            display: none;
        }

        /* Responsive Chart */
        .chart-container {
            background: var(--bg-card);
            border-radius: var(--card-border-radius);
            padding: 20px;
            border: 1px solid var(--border-color);
            height: 100%;
        }

        .chart-placeholder {
            width: 100%;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
        }

        /* Job Posting Card */
        .job-card {
            background: var(--bg-card);
            border-radius: var(--card-border-radius);
            padding: 20px;
            border: 1px solid var(--border-color);
            margin-bottom: 15px;
            transition: all var(--transition-speed);
        }

        .job-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .job-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-active {
            background: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
        }

        .status-pending {
            background: rgba(255, 152, 0, 0.1);
            color: #FF9800;
        }
    </style>
    @stack('create_job_styles')

</head>

<body data-theme="light">


    @include('recruiter.components.dashboard_navbar')

    {{-- Alert  --}}
    @include('components.alert')


    <div class="main-content">
        @yield('content')
    </div>

    @yield('footer')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Toggle sidebar collapse
            $('#toggleCollapse').on('click', function() {
                $('.sidebar').toggleClass('collapsed');

                // Store state in localStorage
                if ($('.sidebar').hasClass('collapsed')) {
                    localStorage.setItem('sidebarCollapsed', 'true');
                } else {
                    localStorage.setItem('sidebarCollapsed', 'false');
                }
            });

            // Check for saved sidebar state
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                $('.sidebar').addClass('collapsed');
            }

            // Mobile menu toggle
            $('.mobile-menu-btn').on('click', function() {
                var myOffcanvas = new bootstrap.Offcanvas(document.getElementById('mobileMenu'));
                myOffcanvas.show();
            });

            // Auto-close mobile menu when clicking a link
            $('.offcanvas-body .nav-link').on('click', function() {
                var offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('mobileMenu'));
                offcanvas.hide();
            });

            // Theme toggle functionality
            $('#themeToggle').change(function() {
                if ($(this).is(':checked')) {
                    $('body').attr('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    $('body').attr('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                }
            });

            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            $('body').attr('data-theme', savedTheme);
            if (savedTheme === 'dark') {
                $('#themeToggle').prop('checked', true);
            }

            // Detect zoom level
            function detectZoom() {
                const ratio = window.devicePixelRatio;
                if (ratio > 1.2) {
                    $('#zoomWarning').show();
                } else {
                    $('#zoomWarning').hide();
                }
            }

            // Initial detection
            detectZoom();

            // Update on resize
            $(window).resize(detectZoom);

            // Detect device mode and update indicator
            function updateDeviceIndicator() {
                if (window.innerWidth < 768) {
                    $('#deviceMode').html('Mobile View');
                    $('.device-indicator i').attr('class', 'bi bi-phone');
                } else if (window.innerWidth < 992) {
                    $('#deviceMode').html('Tablet View');
                    $('.device-indicator i').attr('class', 'bi bi-tablet');
                } else {
                    $('#deviceMode').html('Desktop View');
                    $('.device-indicator i').attr('class', 'bi bi-display');
                }
            }

            // Initial detection
            updateDeviceIndicator();

            // Update on resize
            $(window).resize(updateDeviceIndicator);
        });
    </script>
    @stack('create_job_scripts')
    @stack('job_edit_scripts')
    @stack('recruiter_msg_scripts')


</body>

</html>
