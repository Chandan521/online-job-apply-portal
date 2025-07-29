<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('site_name', config('app.name')) }}@hasSection('title')
            | @yield('title')
        @endif
    </title>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    {{-- Editor  --}}
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    {{-- Editor Script --}}
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
    @stack('view_job_styles')
    @stack('job_application_styles')
    @stack('view_user_style')
    @stack('view_recruiter_styles')

</head>

<body data-theme="light">


    @include('recruiter.components.dashboard_navbar')

    <!-- Main Content -->
    {{-- <div class="main-content">
        <div class="dashboard-header">
            <h2 class="fw-bold">Recruiter Dashboard</h2>
            <p class="mb-0" style="color: var(--text-secondary);">Welcome back, Sarah. Here's what's happening with
                your job postings today.</p>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card">
                    <i class="bi bi-briefcase"></i>
                    <h5>Active Jobs</h5>
                    <h3 class="fw-bold">12</h3>
                    <p class="text-success small"><i class="bi bi-arrow-up"></i> 2 new this week</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card">
                    <i class="bi bi-people"></i>
                    <h5>Applications</h5>
                    <h3 class="fw-bold">84</h3>
                    <p class="text-success small"><i class="bi bi-arrow-up"></i> 12 today</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card">
                    <i class="bi bi-calendar-check"></i>
                    <h5>Interviews</h5>
                    <h3 class="fw-bold">7</h3>
                    <p class="text-warning small"><i class="bi bi-exclamation-circle"></i> 2 scheduled today</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card">
                    <i class="bi bi-star"></i>
                    <h5>Top Candidates</h5>
                    <h3 class="fw-bold">5</h3>
                    <p class="text-info small">Ready for final review</p>
                </div>
            </div>
        </div>

        <!-- Charts and Activity Section -->
        <div class="row mb-4">
            <!-- Chart Column -->
            <div class="col-lg-8 mb-4">
                <div class="chart-container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Application Trends</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary me-1">Week</button>
                            <button class="btn btn-sm btn-outline-primary me-1">Month</button>
                            <button class="btn btn-sm btn-outline-secondary">Quarter</button>
                        </div>
                    </div>
                    <div class="chart-placeholder">
                        <div class="text-center">
                            <i class="bi bi-bar-chart-line display-4 mb-3" style="color: var(--primary-color);"></i>
                            <p>Application trend chart visualization</p>
                            <small class="text-muted">Shows applications received over selected time period</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Column -->
            <div class="col-lg-4 mb-4">
                <div class="activity-card">
                    <h5 class="mb-4">Recent Activity</h5>

                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <div class="activity-content">
                            <h6>New Candidate</h6>
                            <p>Michael Chen applied for Frontend Developer</p>
                            <small>15 min ago</small>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="bi bi-calendar2-check"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Interview Scheduled</h6>
                            <p>Interview with Jane Smith at 2:30 PM</p>
                            <small>2 hours ago</small>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Document Uploaded</h6>
                            <p>John Doe uploaded resume for review</p>
                            <small>4 hours ago</small>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="bi bi-chat-dots"></i>
                        </div>
                        <div class="activity-content">
                            <h6>New Message</h6>
                            <p>Robert Taylor sent a new message</p>
                            <small>6 hours ago</small>
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <a href="#" class="text-decoration-none">View All Activity</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Postings and Quick Actions -->
        <div class="row">
            <!-- Job Postings -->
            <div class="col-lg-8 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5>Active Job Postings</h5>
                    <button class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> New Job</button>
                </div>

                <div class="job-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1">Senior Frontend Developer</h6>
                            <p class="mb-1 text-muted small">React, TypeScript, Redux</p>
                            <span class="job-status status-active">Active</span>
                        </div>
                        <div class="text-end">
                            <div class="mb-1">24 Applications</div>
                            <div class="progress" style="width: 150px;">
                                <div class="progress-bar" style="width: 65%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="job-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1">UX/UI Designer</h6>
                            <p class="mb-1 text-muted small">Figma, Adobe XD, User Research</p>
                            <span class="job-status status-active">Active</span>
                        </div>
                        <div class="text-end">
                            <div class="mb-1">18 Applications</div>
                            <div class="progress" style="width: 150px;">
                                <div class="progress-bar" style="width: 45%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="job-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1">Backend Engineer (Node.js)</h6>
                            <p class="mb-1 text-muted small">Node.js, Express, MongoDB</p>
                            <span class="job-status status-pending">Pending Review</span>
                        </div>
                        <div class="text-end">
                            <div class="mb-1">8 Applications</div>
                            <div class="progress" style="width: 150px;">
                                <div class="progress-bar" style="width: 25%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <a href="#" class="text-decoration-none">View All Jobs</a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4 mb-4">
                <h5 class="mb-4">Quick Actions</h5>

                <div class="row">
                    <div class="col-md-6 col-sm-6 mb-3">
                        <a href="#" class="text-decoration-none">
                            <div class="quick-action">
                                <i class="bi bi-briefcase"></i>
                                <p>Post New Job</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-6 mb-3">
                        <a href="#" class="text-decoration-none">
                            <div class="quick-action">
                                <i class="bi bi-search"></i>
                                <p>Search Candidates</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-6 mb-3">
                        <a href="#" class="text-decoration-none">
                            <div class="quick-action">
                                <i class="bi bi-calendar-plus"></i>
                                <p>Schedule Interview</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-6 mb-3">
                        <a href="#" class="text-decoration-none">
                            <div class="quick-action">
                                <i class="bi bi-envelope"></i>
                                <p>Send Message</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-6 mb-3">
                        <a href="#" class="text-decoration-none">
                            <div class="quick-action">
                                <i class="bi bi-file-earmark-text"></i>
                                <p>Generate Report</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-6 mb-3">
                        <a href="#" class="text-decoration-none">
                            <div class="quick-action">
                                <i class="bi bi-gear"></i>
                                <p>Settings</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- Alert  --}}
    @include('components.alert')

    <div class="main-content">
        @yield('content')
    </div>

    

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

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
    @stack('dashboard-scripts')
    @stack('analytics-scripts')
    @stack('create_job_scripts')
    @stack('job_application_script')


</body>

</html>
