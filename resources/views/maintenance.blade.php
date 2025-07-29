<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>We'll Be Back Soon - {{ setting('site_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f2f2f2, #ffffff);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .container {
            text-align: center;
            padding: 40px 30px;
            border-radius: 16px;
            background-color: white;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            animation: fadeInUp 1s ease-out;
        }

        .logo {
            width: 70px;
            margin-bottom: 20px;
            animation: scaleUp 0.6s ease-out;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 15px;
            animation: fadeIn 1.2s ease-out;
        }

        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
            animation: fadeIn 1.5s ease-out;
        }

        .footer {
            margin-top: 25px;
            font-size: 13px;
            color: #aaa;
        }

        .refresh-btn {
            margin-top: 20px;
            padding: 10px 25px;
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .refresh-btn:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleUp {
            0% {
                transform: scale(0.6);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @media (max-width: 500px) {
            .container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        @if(setting('site_logo'))
            <img src="{{ asset('storage/' . setting('site_logo')) }}" class="logo" alt="Site Logo">
        @endif

        <h1>🔧 We're Working on It!</h1>
        <p>{{ setting('site_name') }} is currently under maintenance.</p>
        <p>We'll be back online shortly. Thank you for your patience.</p>

        <button class="refresh-btn" onclick="refreshPage(this)">🔄 Refresh</button>

        <div class="footer">
            &copy; {{ now()->year }} {{ setting('site_name') }}. All rights reserved.
        </div>
    </div>

    <script>
        function refreshPage(button) {
            button.disabled = true;
            button.innerText = "Please wait...";
            setTimeout(() => {
                window.location.reload();
            }, 1000); // Wait before reloading
        }
    </script>
</body>
</html>
