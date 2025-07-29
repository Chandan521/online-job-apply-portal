<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('site_name', config('app.name')) }}@hasSection('title') | @yield('title')@endif</title>
   <link rel="icon" type="image/png" sizes="32x32"
        href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('storage/defaults/favicon.png') }}">

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('storage/defaults/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Navbar Style Start   --}}
    <style>
        :root {
            --navbar-bg: #f8f9fa;
        }

        .navbar-custom {
            background-color: var(--navbar-bg);
        }

        .nav-link.active {
            position: relative;
            color: #0d6efd !important;
            font-weight: 600;
            background-color: transparent;
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
            background: #ffffff;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            z-index: 1050;
        }

        .mobile-menu.show {
            transform: translateX(0);
        }

        .mobile-menu-header {
            padding: 1rem;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mobile-menu-body {
            padding: 1rem;
        }

        .mobile-menu .nav-link {
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
            font-weight: 500;
        }

        .mobile-signout {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
            padding-top: 1rem;
        }

        .navbar-nav .nav-link {
            position: relative;
            padding-bottom: 6px;
            transition: all 0.2s ease-in-out;
        }

        .navbar-nav .nav-link::after {
            content: "";
            position: absolute;
            left: 5%;
            bottom: 0;
            height: 2px;
            width: 0;
            border-radius: 2px;
            background-color: #0d6efd;
            /* Bootstrap primary color */
            transition: width 0.3s ease-in-out;
        }

        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 95%;
        }
        .text-primary {
            color: #007bff !important;
        }

        .fw-bold {
            font-weight: bold !important;
        }
    </style>
    @stack('login_recruiter_styles')
    @stack('account_settings_style')
    {{-- Navbar Style End   --}}
</head>

<body class="d-flex flex-column min-vh-100">

    {{-- Navbar --}}
    @include('recruiter.components.recruiter_home_navbar')
    @include('components.alert')
    {{-- Mobile Menu --}}
    {{-- @include('recruiter.components.recruiter_mobile_home_navbar') --}}

    {{-- Main Content --}}
    <main class="flex-fill">
        @yield('content')
    </main>
    <div class="flex-grow-1 d-flex flex-column">
    @include('components.support')
    @yield('footer')
    </div>

    {{-- Footer --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Navabr Script Start   --}}
    <script>
        let lastScroll = 0;
        const navbar = document.getElementById('recruiterNavbar');
        const mobileMenu = document.getElementById('mobileMenu');

        function toggleMobileMenu() {
            mobileMenu.classList.toggle('show');
        }

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            if (currentScroll <= 0) {
                navbar.classList.remove('shadow-lg');
                return;
            }
            if (currentScroll > lastScroll) {
                navbar.classList.add('shadow-lg');
            }
            lastScroll = currentScroll;
        });
    </script>
    {{-- Navabr Script End   --}}
    @stack('account_settings_script')
    @stack('login_recruiter_scripts')
    @stack('login_recruiter_scripts')
    @stack('register_recruiter_scripts')
</body>

</html>
