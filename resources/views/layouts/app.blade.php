<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Investment Trading Simulator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #0b1120;
            color: #e5e7eb;
        }
        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            background: #020617;
            border-bottom: 1px solid #1f2937;
        }
        .nav-title {
            font-weight: 600;
        }
        .nav-links form {
            display: inline;
        }
        .nav-links button, .nav-links a {
            background: transparent;
            border: none;
            color: #9ca3af;
            margin-left: 1rem;
            cursor: pointer;
            text-decoration: none;
        }
        .nav-links button:hover,
        .nav-links a:hover {
            color: #f97316;
        }
        .layout-shell {
            display: flex;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            gap: 1.5rem;
        }
        .sidebar {
            width: 220px;
            background: #020617;
            border-radius: 0.75rem;
            border: 1px solid #1f2937;
            padding: 1.25rem 1rem;
            box-shadow: 0 10px 30px rgba(15,23,42,0.6);
        }
        .sidebar h2 {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #9ca3af;
            margin: 0 0 0.75rem 0.4rem;
        }
        .sidebar-nav {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .sidebar-nav li {
            margin-bottom: 0.35rem;
        }
        .sidebar-nav a {
            display: block;
            padding: 0.45rem 0.75rem;
            border-radius: 0.5rem;
            color: #9ca3af;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .sidebar-nav a:hover {
            background: #111827;
            color: #f97316;
        }
        .sidebar-nav a.active {
            background: #111827;
            color: #f97316;
            font-weight: 600;
        }
        .container {
            flex: 1 1 auto;
        }
        .card {
            background: #020617;
            border-radius: 0.75rem;
            padding: 2rem;
            border: 1px solid #1f2937;
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.7);
        }
        .card h1 {
            margin-top: 0;
            margin-bottom: 1rem;
        }
        .flash {
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            background: #064e3b;
            color: #bbf7d0;
            border: 1px solid #10b981;
        }
        .error-list {
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            background: #451a1a;
            color: #fecaca;
            border: 1px solid #b91c1c;
        }
        .error-list ul {
            margin: 0;
            padding-left: 1.2rem;
        }
    </style>
</head>
<body>
<nav class="nav">
    <div class="nav-title">
        Investment Trading Simulator
    </div>
    <div class="nav-links">
        @if(session()->has('user_id'))
            <span>{{ session('user_name') }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @else
            <a href="{{ route('login.show') }}">Login</a>
            <a href="{{ route('register.show') }}">Register</a>
        @endif
    </div>
</nav>

<div class="layout-shell">
    @if(session()->has('user_id'))
        <aside class="sidebar">
            <h2>Navigation</h2>
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>

                @if(session('user_role') === 'admin')
                    <li style="margin-top:0.75rem;">
                        <h2>Admin</h2>
                    </li>
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            Admin Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}"
                           class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            Users
                        </a>
                    </li>
                @endif
            </ul>
        </aside>
    @endif

    <div class="container">
        @if(session('status'))
            <div class="flash">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>
</body>
</html>

