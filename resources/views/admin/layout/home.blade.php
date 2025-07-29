<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>@yield('title', 'JobPortal Admin')</title>
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('storage/defaults/favicon.png') }}">

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('storage/defaults/favicon.png') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #1a0a3c;
            color: #fff;
            overflow-x: hidden;
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 100px;
            /* Add padding to push content below fixed nav */
        }

        .nav {
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            width: 100%;
            z-index: 100;
            background: #1a0a3c;
            /* Add background to prevent content showing through */
        }

        .nav-logo p {
            font-size: 25px;
            font-weight: 600;
        }

        .nav-menu ul {
            display: flex;
            gap: 40px;
        }

        .nav-menu ul li {
            list-style-type: none;
        }

        .nav-menu ul li .link {
            text-decoration: none;
            font-weight: 500;
            color: #fff;
            padding-bottom: 5px;
        }

        .link.active {
            border-bottom: 2px solid #fff;
        }

        .nav-button .btn {
            padding: 10px 20px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 30px;
            cursor: pointer;
            transition: .3s ease;
            color: #fff;
            margin-left: 10px;
        }

        .btn.white-btn {
            background: #fff;
            color: #1a0a3c;
        }

        .form-box {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            position: relative;
            margin-top: 0;
        }

        .background-shape {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            overflow: hidden;
        }

        .background-shape::before {
            content: '';
            position: absolute;
            width: 1200px;
            height: 1200px;
            border-radius: 50%;
            background: linear-gradient(45deg, #3a1c71, #d76d77, #ffaf7b);
            filter: blur(150px);
            top: -300px;
            left: -300px;
            opacity: 0.5;
        }

        .background-shape::after {
            content: '';
            position: absolute;
            width: 1000px;
            height: 1000px;
            border-radius: 50%;
            background: linear-gradient(45deg, #00c6ff, #0072ff);
            filter: blur(150px);
            bottom: -300px;
            right: -300px;
            opacity: 0.5;
        }

        .login-container,
        .register-container {
            width: 400px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: .5s ease-in-out;
        }

        .register-container {
            position: absolute;
            transform: translateX(150%);
            opacity: 0;
        }

        .login-container.active {
            left: 0;
            opacity: 1;
        }

        .register-container.active {
            right: 0;
            opacity: 1;
        }

        .top {
            text-align: center;
            margin-bottom: 30px;
        }

        .top span {
            font-size: small;
        }

        .top span a {
            font-weight: 500;
            color: #fff;
        }

        header {
            font-size: 30px;
            font-weight: 600;
            margin-top: 10px;
        }

        .input-box {
            position: relative;
            margin-bottom: 20px;
        }

        .input-field {
            width: 100%;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            padding: 0 15px 0 45px;
            outline: none;
            color: #fff;
            font-size: 15px;
        }

        .input-field::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .input-field[name="password"],
        .input-field[name="password_confirmation"] {
            padding-right: 45px;
        }

        .input-box i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 15px;
            color: rgba(255, 255, 255, 0.7);
        }

        .input-box .toggle-password {
            left: auto;
            right: 15px;
            cursor: pointer;
        }

        .submit {
            width: 100%;
            height: 50px;
            background: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            color: #1a0a3c;
            cursor: pointer;
            transition: .3s;
        }

        .submit:hover {
            background: rgba(255, 255, 255, 0.8);
        }

        .two-col {
            display: flex;
            justify-content: space-between;
            font-size: small;
            margin-top: 15px;
        }

        .two-col .one {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .two-col label a {
            color: #fff;
            text-decoration: none;
        }

        .two-col label a:hover {
            text-decoration: underline;
        }

        .two-forms {
            display: flex;
            gap: 10px;
        }

        .alert-danger {
            background: rgba(255, 0, 0, 0.2);
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-danger ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
    </style>
</head>

<body>
    @include('components.alert')
    <div class="wrapper">
        <div class="background-shape"></div>
        <nav class="nav">
            <div class="nav-logo" style="display: flex; align-items: center;">
                <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('storage/defaults/logo.png') }}"
                    alt="{{ setting('site_name') ?? 'HireMe Logo' }}" style="height: 32px; margin-right: 10px;">

                <span style="font-weight: bold; font-size: 18px;">
                    {{ setting('site_name') ?? 'HireMe' }}
                </span>
            </div>

            <div class="nav-menu">
                <ul>
                    <li><a href="{{ route('admin.auth') }}"
                            class="link {{ request()->routeIs('admin.auth') ? 'active' : '' }}">Login/Signup</a></li>
                    <li><a href="{{ route('admin.services') }}"
                            class="link {{ request()->routeIs('admin.services') ? 'active' : '' }}">Services</a></li>
                    <li><a href="{{ route('admin.about') }}"
                            class="link {{ request()->routeIs('admin.about') ? 'active' : '' }}">About</a></li>
                </ul>
            </div>

            <div class="nav-button">
                @if (Route::currentRouteName() === 'admin.auth')
                    <button class="btn white-btn" id="loginBtn" onclick="showLogin()">Sign In</button>
                    <button class="btn" id="registerBtn" onclick="showRegister()">Sign Up</button>
                @else
                    <button class="btn white-btn" id="userBtn"
                        onclick="window.location.href='{{ route('home') }}'">User</button>
                    <button class="btn" id="recruterBtn"
                        onclick="window.location.href='{{ route('hire') }}'">Recruiter</button>
                @endif
            </div>
        </nav>

        @yield('content')

    </div>

    <script>
        const loginContainer = document.getElementById("login");
        const registerContainer = document.getElementById("register");
        const loginBtn = document.getElementById("loginBtn");
        const registerBtn = document.getElementById("registerBtn");

        function showLogin() {
            loginContainer.style.transform = "translateX(0)";
            loginContainer.style.opacity = "1";
            registerContainer.style.transform = "translateX(150%)";
            registerContainer.style.opacity = "0";
            loginBtn.classList.add("white-btn");
            registerBtn.classList.remove("white-btn");
        }

        function showRegister() {
            loginContainer.style.transform = "translateX(-150%)";
            loginContainer.style.opacity = "0";
            registerContainer.style.transform = "translateX(0)";
            registerContainer.style.opacity = "1";
            loginBtn.classList.remove("white-btn");
            registerBtn.classList.add("white-btn");
        }

        function togglePassword(id) {
            const passwordField = document.getElementById(id);
            const toggleIcon = passwordField.nextElementSibling;
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove('bx-hide');
                toggleIcon.classList.add('bx-show');
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove('bx-show');
                toggleIcon.classList.add('bx-hide');
            }
        }

        // Show the correct form on page load if there are errors
        @if ($errors->any())
            @if (session('form') == 'register' || old('firstname'))
                showRegister();
            @else
                showLogin();
            @endif
        @else
            showLogin();
        @endif
    </script>

</body>

</html>
