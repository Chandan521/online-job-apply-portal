<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'JobPortal Admin')</title>
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('storage/defaults/favicon.png') }}">

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ setting('site_favicon') ? asset('storage/' . setting('site_favicon')) : asset('storage/defaults/favicon.png') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f5f5f5;
        }

        .wrapper {
            position: relative;
            width: 400px;
            height: 500px;
            background: #fff;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            padding: 40px;
            overflow: hidden;
        }

        .wrapper.active {
            height: 600px;
        }

        .form-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            transition: 1s ease-in-out;
        }

        .wrapper.active .form-wrapper.login {
            transform: translateY(-600px);
        }

        .wrapper .form-wrapper.register {
            position: absolute;
            top: 600px;
            left: 0;
        }

        .wrapper.active .form-wrapper.register {
            transform: translateY(-600px);
        }

        .top {
            margin-bottom: 20px;
            text-align: center;
        }

        .top span {
            font-size: 14px;
            color: #555;
        }

        .top span a {
            color: #6c63ff;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }

        .top header {
            font-size: 30px;
            font-weight: 600;
            color: #333;
            margin-top: 10px;
        }

        .input-box {
            position: relative;
            width: 100%;
            margin: 20px 0;
        }

        .input-box i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }

        .input-box .left-icon {
            left: 15px;
        }

        .input-box .right-icon {
            right: 15px;
            cursor: pointer;
        }

        .input-box input {
            width: 100%;
            padding: 12px 45px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 30px;
            outline: none;
            font-size: 16px;
            transition: 0.3s;
        }

        .input-box.password-input input {
            padding-right: 65px;
            /* Extra space for the toggle icon */
        }

        .input-box input:focus {
            border-color: #6c63ff;
            box-shadow: 0 0 10px rgba(108, 99, 255, 0.2);
        }

        .submit {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 30px;
            background: #6c63ff;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit:hover {
            background: #5a52d5;
        }

        .two-forms {
            display: flex;
            gap: 10px;
        }

        .two-forms .input-box {
            width: 100%;
        }

        .two-col {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            margin-top: 10px;
        }

        .two-col .one {
            display: flex;
            align-items: center;
        }

        .two-col .one input {
            margin-right: 5px;
        }

        .two-col label a {
            color: #6c63ff;
            text-decoration: none;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper" id="auth-wrapper">
        <div class="form-wrapper login">
            <form action="{{ route('admin.login') }}" method="POST" autocomplete="off">
                @csrf
                <div class="top">
                    <span>Don't have an account? <a href="#" onclick="showRegister()">Sign Up</a></span>
                    <header>Login</header>
                </div>
                @if ($errors->any() && session('form') == 'login')
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="input-box">
                    <i class='bx bx-user left-icon'></i>
                    <input type="text" name="email" class="input-field" placeholder="Email" autocomplete="off"
                        value="{{ old('email') }}">
                </div>
                <div class="input-box password-input">
                    <i class='bx bx-lock-alt left-icon'></i>
                    <input type="password" name="password" id="password-login" class="input-field"
                        placeholder="Password" autocomplete="new-password">
                    <i class='bx bx-hide right-icon toggle-password'
                        onclick="togglePassword('password-login', this)"></i>
                </div>
                <div class="input-box">
                    <input type="submit" class="submit" value="Sign In">
                </div>
                <div class="two-col">
                    <div class="one">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="login-check"> Remember Me</label>
                    </div>
                    <div class="two">
                        <label><a href="#">Forgot password?</a></label>
                    </div>
                </div>
            </form>
        </div>

        <div class="form-wrapper register">
            <form action="{{ route('admin.register') }}" method="POST" autocomplete="off">
                @csrf
                <div class="top">
                    <span>Have an account? <a href="#" onclick="showLogin()">Login</a></span>
                    <header>Sign Up</header>
                </div>
                @if ($errors->any() && session('form') == 'register')
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="two-forms">
                    <div class="input-box">
                        <i class='bx bx-user left-icon'></i>
                        <input type="text" name="firstname" id="firstname" class="input-field"
                            placeholder="Firstname" autocomplete="off" value="{{ old('firstname') }}">
                    </div>
                    <div class="input-box">
                        <i class='bx bx-user left-icon'></i>
                        <input type="text" name="lastname" id="lastname" class="input-field" placeholder="Lastname"
                            autocomplete="off" value="{{ old('lastname') }}">
                    </div>
                </div>
                <div class="input-box">
                    <i class='bx bx-envelope left-icon'></i>
                    <input type="email" name="email" class="input-field" placeholder="Email" autocomplete="off"
                        value="{{ old('email') }}">
                </div>
                <div class="input-box password-input">
                    <i class='bx bx-lock-alt left-icon'></i>
                    <input type="password" name="password" id="password-register" class="input-field"
                        placeholder="Password" autocomplete="new-password">
                    <i class='bx bx-hide right-icon toggle-password'
                        onclick="togglePassword('password-register', this)"></i>
                </div>
                <div class="input-box password-input">
                    <i class='bx bx-lock-alt left-icon'></i>
                    <input type="password" name="password_confirmation" id="password-confirm" class="input-field"
                        placeholder="Confirm Password" autocomplete="new-password">
                    <i class='bx bx-hide right-icon toggle-password'
                        onclick="togglePassword('password-confirm', this)"></i>
                </div>
                <div class="input-box">
                    <input type="submit" class="submit" value="Register">
                </div>
                <div class="two-col">
                    <div class="one">
                        <input type="checkbox" id="register-check" required>
                        <label for="register-check"> I agree to the <a href="#">Terms & Conditions</a></label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRegister() {
            document.getElementById('auth-wrapper').classList.add('active');
        }

        function showLogin() {
            document.getElementById('auth-wrapper').classList.remove('active');
        }

        function togglePassword(fieldId, iconElement) {
            const passwordField = document.getElementById(fieldId);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                iconElement.classList.replace('bx-hide', 'bx-show');
            } else {
                passwordField.type = 'password';
                iconElement.classList.replace('bx-show', 'bx-hide');
            }
        }

        // Show login or register form based on errors
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any() && session('form') == 'register')
                showRegister();
            @endif
        });
    </script>
</body>

</html>
