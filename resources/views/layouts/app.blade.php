<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Investment Trading Simulator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root {
            --bg: #020617;
            --bg-alt: #0b1120;
            --card: #020617;
            --border: #1f2937;
            --accent: #6366f1;
            --accent-soft: rgba(99,102,241,0.18);
            --accent-strong: #4f46e5;
            --text: #e5e7eb;
            --muted: #9ca3af;
            --danger-soft: rgba(239,68,68,0.12);
            --danger-border: rgba(239,68,68,0.6);
            --success-soft: rgba(34,197,94,0.12);
            --success-border: rgba(34,197,94,0.6);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, #1e293b 0, #020617 55%);
            color: var(--text);
        }

        a {
            color: inherit;
        }

        .shell {
            display: flex;
            min-height: 100vh;
        }

        .sidebar-shell {
            width: 250px;
            background: linear-gradient(to bottom, #020617, #020617 40%, #020617);
            border-right: 1px solid #111827;
            display: flex;
            flex-direction: column;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 1.2rem 1.4rem;
            border-bottom: 1px solid #111827;
        }

        .sidebar-logo-icon {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            background: radial-gradient(circle at 30% 30%, #fcd34d, #f97316 45%, #7c2d12 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            color: #020617;
        }

        .sidebar-logo-text {
            display: flex;
            flex-direction: column;
        }

        .sidebar-logo-title {
            font-size: 0.95rem;
            font-weight: 600;
        }

        .sidebar-logo-subtitle {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .sidebar-main {
            flex: 1 1 auto;
            padding: 1rem 0.9rem 1.2rem;
            overflow-y: auto;
        }

        .sidebar-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--muted);
            margin: 0.75rem 0.4rem 0.35rem;
        }

        .sidebar-nav {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .sidebar-item {
            margin-bottom: 0.25rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.45rem 0.75rem;
            border-radius: 0.55rem;
            text-decoration: none;
            color: var(--muted);
            font-size: 0.9rem;
            transition: background 0.15s, color 0.15s, transform 0.05s;
        }

        .sidebar-link:hover {
            background: #111827;
            color: var(--text);
            transform: translateX(1px);
        }

        .sidebar-link.active {
            background: var(--accent-soft);
            color: var(--accent-strong);
            font-weight: 600;
        }

        .sidebar-icon {
            width: 18px;
            height: 18px;
            border-radius: 999px;
            border: 1px solid #4b5563;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }

        .sidebar-footer {
            padding: 0.8rem 1rem 1.1rem;
            border-top: 1px solid #111827;
            font-size: 0.8rem;
            color: var(--muted);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
        }

        .sidebar-user-name {
            font-weight: 500;
        }

        .sidebar-role-pill {
            padding: 0.1rem 0.5rem;
            border-radius: 999px;
            border: 1px solid #374151;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .sidebar-logout-form button {
            background: transparent;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 0.8rem;
        }

        .main-shell {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0.8rem 1.5rem;
            border-bottom: 1px solid #111827;
            background: rgba(15,23,42,0.7);
            backdrop-filter: blur(10px);
        }

        .topbar-pill {
            padding: 0.2rem 0.75rem;
            border-radius: 999px;
            border: 1px solid #1f2937;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .content-shell {
            flex: 1 1 auto;
            padding: 1.5rem 1.8rem 2rem;
            overflow-y: auto;
        }

        .flash {
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.6rem;
            background: var(--success-soft);
            border: 1px solid var(--success-border);
            color: #bbf7d0;
            font-size: 0.9rem;
        }

        .card {
            background: radial-gradient(circle at top left, rgba(148,163,184,0.1), transparent 55%), var(--card);
            border-radius: 1rem;
            padding: 1.8rem 1.6rem 1.6rem;
            border: 1px solid var(--border);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.9);
        }

        .card h1 {
            margin-top: 0;
            margin-bottom: 0.4rem;
            font-size: 1.4rem;
        }

        .card-subtitle {
            margin: 0;
            margin-bottom: 1.2rem;
            font-size: 0.9rem;
            color: var(--muted);
        }

        .auth-shell {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
        }

        .auth-card {
            max-width: 420px;
            width: 100%;
        }

        .error-list {
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.6rem;
            background: var(--danger-soft);
            border: 1px solid var(--danger-border);
            color: #fecaca;
            font-size: 0.9rem;
        }

        .error-list ul {
            margin: 0;
            padding-left: 1.2rem;
        }

        .field {
            margin-bottom: 0.9rem;
        }

        .field label {
            display: block;
            font-size: 0.8rem;
            color: var(--muted);
            margin-bottom: 0.2rem;
        }

        .field input {
            width: 100%;
            padding: 0.55rem 0.7rem;
            border-radius: 0.55rem;
            border: 1px solid #374151;
            background: #020617;
            color: var(--text);
            font-size: 0.9rem;
        }

        .field input:focus {
            outline: none;
            border-color: var(--accent-strong);
            box-shadow: 0 0 0 1px var(--accent-strong);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.6rem 1.4rem;
            border-radius: 999px;
            border: none;
            background: linear-gradient(to right, var(--accent-strong), #f97316);
            color: #020617;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(8,47,73,0.8);
        }

        .btn-secondary-link {
            display: inline-block;
            margin-left: 0.75rem;
            font-size: 0.8rem;
            color: var(--muted);
            text-decoration: none;
        }

        .btn-secondary-link:hover {
            color: var(--accent);
        }

        .muted-text {
            color: var(--muted);
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
@if(request()->routeIs('login.show') || request()->routeIs('register.show'))
    <div class="auth-shell">
        <div class="card auth-card">
            @yield('content')
        </div>
    </div>
@else
    <div class="shell">
        <aside class="sidebar-shell">
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    IS
                </div>
                <div class="sidebar-logo-text">
                    <div class="sidebar-logo-title">Investment Simulator</div>
                    <div class="sidebar-logo-subtitle">Practice before risking cash</div>
                </div>
            </div>

            <div class="sidebar-main">
                @include('partials.sidebar')
            </div>

            @if(session()->has('user_id'))
                <div class="sidebar-footer">
                    <div class="sidebar-user">
                        <div>
                            <div class="sidebar-user-name">{{ session('user_name') }}</div>
                            <div class="sidebar-role-pill">{{ strtoupper(session('user_role')) }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="sidebar-logout-form">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    </div>
                </div>
            @endif
        </aside>

        <div class="main-shell">
            <header class="topbar">
                <div class="topbar-pill">
                    Beginner trading sandbox · Prices are simulated
                </div>
            </header>

            <main class="content-shell">
                @if(session('status'))
                    <div class="flash">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
@endif
</body>
</html>

