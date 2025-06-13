<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'MyFinance') }} - @yield('title')</title>
    <style>
        :root {
            --primary: #1E2A38;
            --secondary: #0C7C59;
            --accent: #F4B400;
            --background: #F5F7FA;
            --surface: #FFFFFF;
            --text-main: #2D2D2D;
            --text-muted: #6C757D;
            --error: #D93025;
            --success: #34A853;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            line-height: 1.6;
        }

        .container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .card {
            background: var(--surface);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .card h1 {
            color: var(--primary);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-main);
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 2px rgba(12, 124, 89, 0.1);
        }

        .btn {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .alert {
            padding: 0.75rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .alert-danger {
            background-color: rgba(217, 48, 37, 0.1);
            border: 1px solid var(--error);
            color: var(--error);
        }

        .link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: var(--secondary);
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }

        .radio-group {
            display: flex;
            gap: 1.5rem;
            margin-top: 0.5rem;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .radio-option input[type="radio"] {
            width: auto;
            margin: 0;
        }

        .radio-option label {
            margin: 0;
            cursor: pointer;
            color: var(--text-main);
        }

        .radio-option input[type="radio"]:checked + label {
            color: var(--secondary);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <nav class="nav">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="nav-brand">MyFinance</a>
            
            <div class="nav-menu">
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                    <a href="{{ url('/transactions') }}" class="nav-link">Transactions</a>
                    <form method="POST" action="{{ url('/logout') }}" class="nav-item">
                        @csrf
                        <button type="submit" class="btn-link">Logout</button>
                    </form>
                @else
                    <a href="{{ url('/login') }}" class="nav-link">Login</a>
                    <a href="{{ url('/register') }}" class="nav-link">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <style>
        .nav {
            background-color: var(--primary);
            padding: 1rem 0;
            margin-bottom: 2rem;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-brand {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: white;
        }

        .btn-link {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
        }

        .btn-link:hover {
            color: white;
        }
    </style>
</body>
</html>
