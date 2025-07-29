<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>419 - Page Expired</title>
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
            color: #ffc107;
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
        <div class="error-code">419</div>
        <div class="error-message">Page Expired</div>
        <p class="text-muted">Your session has expired. Please refresh the page and try again.</p>
        <a href="{{ url()->previous() }}" class="btn btn-warning btn-home">
            <i class="fas fa-arrow-left mr-1"></i> Go Back
        </a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

</body>
</html>
