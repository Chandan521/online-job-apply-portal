<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 4.6 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .error-container {
            text-align: center;
            padding: 40px;
        }
        .error-code {
            font-size: 8rem;
            font-weight: bold;
            color: #dc3545;
        }
        .error-message {
            font-size: 1.5rem;
            color: #333;
        }
        .btn-home {
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">Oops! Page not found.</div>
        <p class="text-muted">The page you're looking for might have been removed or is temporarily unavailable.</p>
        <a href="{{ url('/') }}" class="btn btn-primary btn-home">
            <i class="fas fa-home mr-1"></i> Go Home
        </a>
    </div>

    <!-- Font Awesome for icon (optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

</body>
</html>
