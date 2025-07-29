<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ setting('site_name', config('app.name')) }}@hasSection('title')
            | @yield('title')
        @endif
    </title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('storage/defaults/favicon.png') }}">

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('storage/defaults/favicon.png') }}">

    <!-- Web Manifest (Optional) -->
    {{-- <link rel="manifest" href="/site.webmanifest"> --}}

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome (for password toggle eye icon) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Intl Tel Input CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.min.css" />


    {{-- Page-specific styles --}}
    @yield('salaries_css')
    @stack('full-view-style')
    @yield('saved_style')
    @stack('notification_style')
    @yield('job_apply_style')
    @yield('user_login_css')
    @yield('user_signup_css')
    @yield('jobs_style')
    @yield('job_search_style')
    @stack('blog_show_style')
    <style>
        /* --- Navbar Styles --- */
        .no-caret::after {
            display: none !important;
        }

        .navbar-brand {
            padding: 4px 8px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .navbar-brand:hover {
            background-color: rgba(0, 123, 255, 0.2);
        }

        .navbar-nav .nav-link {
            font-size: 1.1rem;
            font-weight: 500;
            position: relative;
            padding-bottom: 6px;
        }

        .navbar-nav .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2px;
            width: 100%;
            background-color: #0d6efd;
        }

        .text-primary {
            color: #007bff !important;
        }

        .fw-bold {
            font-weight: bold !important;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2px;
            width: 0;
            background-color: #0d6efd;
            transition: width 0.3s ease;
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        .navbar-nav .nav-link i {
            font-size: 1.3rem;
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                text-align: center;
            }

            .navbar-nav {
                flex-direction: column !important;
            }

            .navbar-nav .nav-item {
                margin-bottom: 10px;
            }

            .navbar-nav .btn {
                width: 100%;
                margin-top: 10px;
            }

            .navbar-nav .dropdown-menu {
                width: 90%;
                margin: 0 auto;
            }

            #jobDetail {
                margin-top: 2rem;
            }
        }

        .active-job {
            background-color: rgba(13, 110, 253, 0.05);
            box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        /* --- Main Content --- */
        main {
            flex: 1 0 auto;
            padding-top: 2rem;
            padding-bottom: 2rem;
            background: #f8f9fa;
        }

        /* --- Footer --- */
        footer {
            background: #f8f9fa;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.03);
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Main Content --}}
    <main>
        @include('components.alert')
        @yield('content')
    </main>

    {{-- Footer --}}
    @yield('footer')
    {{-- Page-specific scripts --}}
    @stack('login_recruiter_scripts')
    @yield('reset_password_script')
    @stack('scripts')
    @yield('job_apply_script')
    @yield('user_login_scripts')
    @yield('user_signup_scripts')
    @yield('job_search_scripts')
    @yield('conversation_script')
    @yield('like_dislike_script')
    <!-- JS Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enable Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl, {
                    delay: {
                        show: 100,
                        hide: 50
                    }
                });
            });
        });
    </script>
    {{-- like dislike  --}}

    <script>
        // Save job ID to localStorage
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.save-job-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const jobId = this.getAttribute('data-job-id');
                    let savedJobs = JSON.parse(localStorage.getItem('saved_jobs') || '[]');

                    if (!savedJobs.includes(jobId)) {
                        savedJobs.push(jobId);
                        localStorage.setItem('saved_jobs', JSON.stringify(savedJobs));
                        this.classList.remove('btn-outline-secondary');
                        this.classList.add('btn-success');
                        this.innerHTML = '<i class="bi bi-bookmark-check"></i>';
                    } else {
                        // Optional: allow un-saving
                        savedJobs = savedJobs.filter(id => id != jobId);
                        localStorage.setItem('saved_jobs', JSON.stringify(savedJobs));
                        this.classList.add('btn-outline-secondary');
                        this.classList.remove('btn-success');
                        this.innerHTML = '<i class="bi bi-bookmark"></i>';
                    }
                });

                // Set state on load
                const jobId = button.getAttribute('data-job-id');
                const savedJobs = JSON.parse(localStorage.getItem('saved_jobs') || '[]');
                if (savedJobs.includes(jobId)) {
                    button.classList.remove('btn-outline-secondary');
                    button.classList.add('btn-success');
                    button.innerHTML = '<i class="bi bi-bookmark-check"></i>';
                }
            });
        });
    </script>


    @stack('saved_scripts')
    @stack('notification_scripts')

</body>

</html>
