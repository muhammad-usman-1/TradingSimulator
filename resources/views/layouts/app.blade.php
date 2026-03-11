<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TradeSimulator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --bg:           #030712;
            --bg-alt:       #06091a;
            --sidebar-bg:   #040a14;
            --card:         #0b1526;
            --card-alt:     #0f1d35;
            --border:       rgba(148,163,184,.09);
            --border-md:    rgba(148,163,184,.15);
            --accent:       #6366f1;
            --accent-soft:  rgba(99,102,241,.14);
            --green:        #10b981;
            --green-soft:   rgba(16,185,129,.12);
            --red:          #ef4444;
            --red-soft:     rgba(239,68,68,.12);
            --amber:        #f59e0b;
            --amber-soft:   rgba(245,158,11,.12);
            --text:         #e2e8f0;
            --muted:        #64748b;
            --dim:          #475569;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }

        body {
            font-family: system-ui, -apple-system, 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 52px 52px;
            pointer-events: none; z-index: 0;
        }

        a { color: inherit; text-decoration: none; }

        /* ── Shell ── */
        .shell { display: flex; min-height: 100vh; position: relative; z-index: 1; }

        /* ── Sidebar ── */
        .sidebar {
            width: 236px; flex-shrink: 0;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: sticky; top: 0; height: 100vh;
            overflow-y: auto;
        }

        .sidebar-logo {
            display: flex; align-items: center; gap: .75rem;
            padding: 1.2rem 1.1rem;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-logo-icon {
            width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
            background: linear-gradient(135deg, #6366f1, #10b981);
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: .82rem; color: #fff;
            box-shadow: 0 0 18px rgba(16,185,129,.2);
        }
        .sidebar-app-name { font-size: .92rem; font-weight: 700; color: var(--text); }
        .sidebar-app-tag  { font-size: .65rem; color: var(--dim); margin-top: .05rem; }

        .sidebar-nav { flex: 1; padding: .85rem .7rem; display: flex; flex-direction: column; gap: .1rem; }

        .snav-section {
            font-size: .65rem; font-weight: 700; color: var(--dim);
            text-transform: uppercase; letter-spacing: .09em;
            padding: .8rem .55rem .3rem;
        }

        .snav-link {
            display: flex; align-items: center; gap: .6rem;
            padding: .48rem .65rem; border-radius: .55rem;
            color: var(--muted); font-size: .855rem; font-weight: 500;
            transition: background .14s, color .14s;
            position: relative;
        }
        .snav-link:hover  { background: rgba(148,163,184,.06); color: var(--text); }
        .snav-link.active { background: var(--accent-soft); color: #818cf8; }
        .snav-link.active::before {
            content: '';
            position: absolute; left: 0; top: 22%; bottom: 22%;
            width: 3px; border-radius: 0 3px 3px 0;
            background: var(--accent);
        }
        .snav-icon { width: 16px; height: 16px; flex-shrink: 0; opacity: .65; }
        .snav-link.active .snav-icon,
        .snav-link:hover  .snav-icon { opacity: 1; }

        .sidebar-footer {
            padding: .85rem 1rem 1rem;
            border-top: 1px solid var(--border);
        }
        .sf-name { font-size: .82rem; font-weight: 700; color: var(--text); }
        .sf-role {
            display: inline-block; margin-top: .2rem;
            font-size: .6rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .07em; color: var(--muted);
            background: rgba(148,163,184,.08); border: 1px solid var(--border);
            border-radius: 999px; padding: .1rem .45rem;
        }
        .sf-logout {
            margin-top: .6rem; background: none; border: none; cursor: pointer;
            color: var(--dim); font-size: .77rem; font-weight: 500;
            display: flex; align-items: center; gap: .35rem;
            transition: color .14s; padding: 0;
        }
        .sf-logout:hover { color: var(--red); }

        /* ── Main ── */
        .main { flex: 1; min-width: 0; display: flex; flex-direction: column; }

        .topbar {
            display: flex; align-items: center; justify-content: space-between;
            padding: .7rem 1.75rem;
            border-bottom: 1px solid var(--border);
            background: rgba(4,10,20,.85);
            backdrop-filter: blur(14px);
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-left { display: flex; align-items: center; gap: .6rem; }
        .topbar-dot {
            width: 7px; height: 7px; border-radius: 999px;
            background: var(--green);
            animation: topDotBlink 2s ease-in-out infinite;
        }
        @keyframes topDotBlink {
            0%, 100% { opacity: 1; } 50% { opacity: .3; }
        }
        .topbar-label { font-size: .78rem; color: var(--muted); }
        .topbar-right { display: flex; align-items: center; gap: .65rem; }
        .topbar-balance {
            font-size: .8rem; font-weight: 700; font-family: monospace;
            color: var(--green); background: var(--green-soft);
            border: 1px solid rgba(16,185,129,.2);
            padding: .22rem .8rem; border-radius: 999px;
        }
        .topbar-pill {
            font-size: .72rem; color: var(--muted);
            background: rgba(148,163,184,.06); border: 1px solid var(--border);
            padding: .22rem .75rem; border-radius: 999px;
        }

        .content { flex: 1; padding: 1.75rem 2rem 3rem; overflow-y: auto; }

        /* ── Flash ── */
        .flash {
            margin-bottom: 1.25rem; padding: .7rem 1rem;
            border-radius: .7rem;
            background: var(--green-soft); border: 1px solid rgba(16,185,129,.22);
            color: #6ee7b7; font-size: .86rem;
            display: flex; align-items: center; gap: .5rem;
        }
        .flash-error {
            background: var(--red-soft); border: 1px solid rgba(239,68,68,.22);
            color: #fca5a5;
        }

        /* ── Auth shell (login/register use standalone pages, this is fallback) ── */
        .auth-shell {
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; padding: 1.5rem;
        }

        /* ══════════════════════════════════════
           DESIGN SYSTEM
        ══════════════════════════════════════ */

        /* Page header */
        .page-header { margin-bottom: 1.75rem; }
        .page-title  { font-size: 1.55rem; font-weight: 800; color: var(--text); letter-spacing: -.025em; }
        .page-sub    { font-size: .875rem; color: var(--muted); margin-top: .3rem; line-height: 1.5; }

        /* Cards */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 1rem; padding: 1.5rem;
        }
        .card h1, .card h2 { color: var(--text); margin-top: 0; }
        .card-subtitle { font-size: .875rem; color: var(--muted); margin-top: .25rem; margin-bottom: .5rem; }

        /* Metric row */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(175px, 1fr));
            gap: .9rem; margin-bottom: 1.5rem;
        }
        .metric {
            background: var(--card); border: 1px solid var(--border);
            border-radius: .9rem; padding: 1.1rem 1.2rem;
            transition: border-color .2s;
        }
        .metric:hover { border-color: var(--border-md); }
        .metric.green { border-color: rgba(16,185,129,.2); }
        .metric.red   { border-color: rgba(239,68,68,.2); }
        .metric.blue  { border-color: rgba(99,102,241,.2); }
        .metric-label {
            font-size: .68rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: .07em; margin-bottom: .45rem;
        }
        .metric.green .metric-label { color: var(--green); }
        .metric.red   .metric-label { color: var(--red);   }
        .metric.blue  .metric-label { color: #818cf8;      }
        .metric-value {
            font-size: 1.5rem; font-weight: 800; color: var(--text);
            font-family: 'SF Mono', Monaco, 'Courier New', monospace;
            letter-spacing: -.02em;
        }
        .metric-sub { font-size: .73rem; color: var(--muted); margin-top: .2rem; }

        /* Section title */
        .section-title {
            font-size: .72rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: .09em;
            margin-bottom: .9rem;
            display: flex; align-items: center; gap: .5rem;
        }
        .section-title::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }

        /* Tables */
        .ds-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        .ds-table th {
            text-align: left; padding: .65rem .9rem;
            font-size: .67rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: .08em;
            border-bottom: 1px solid var(--border);
            background: rgba(148,163,184,.03);
        }
        .ds-table td { padding: .72rem .9rem; border-bottom: 1px solid rgba(148,163,184,.05); color: var(--text); }
        .ds-table tr:last-child td { border-bottom: none; }
        .ds-table tr:hover td { background: rgba(148,163,184,.03); }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center;
            padding: .15rem .55rem; border-radius: 999px;
            font-size: .68rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .05em;
        }
        .badge-buy  { background: rgba(16,185,129,.14); color: #10b981; border: 1px solid rgba(16,185,129,.22); }
        .badge-sell { background: rgba(239,68,68,.14);  color: #ef4444; border: 1px solid rgba(239,68,68,.22);  }
        .badge-info { background: rgba(99,102,241,.14); color: #818cf8; border: 1px solid rgba(99,102,241,.22); }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
            padding: .58rem 1.2rem; border-radius: .65rem;
            font-size: .875rem; font-weight: 600; cursor: pointer; border: none;
            transition: opacity .14s, transform .1s, box-shadow .14s;
        }
        .btn:hover  { opacity: .9; transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,.28);
        }
        .btn-buy  {
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff; box-shadow: 0 4px 12px rgba(16,185,129,.22);
        }
        .btn-sell {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: #fff; box-shadow: 0 4px 12px rgba(239,68,68,.22);
        }
        .btn-ghost {
            background: rgba(148,163,184,.08); color: var(--muted);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { background: rgba(148,163,184,.13); color: var(--text); }
        .btn-sm { padding: .32rem .75rem; font-size: .78rem; border-radius: .55rem; }
        .btn-secondary-link {
            display: inline-flex; align-items: center;
            margin-left: .75rem; font-size: .82rem;
            color: var(--muted);
        }
        .btn-secondary-link:hover { color: var(--accent); }

        /* Text helpers */
        .text-green { color: #10b981; }
        .text-red   { color: #ef4444; }
        .text-muted { color: var(--muted); }
        .text-accent { color: #818cf8; }
        .text-mono  { font-family: 'SF Mono', Monaco, 'Courier New', monospace; }

        /* Layout utils */
        .flex-row     { display: flex; align-items: center; gap: .75rem; }
        .flex-between { display: flex; align-items: center; justify-content: space-between; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }
        .mt-2 { margin-top: 1rem; }
        .mt-3 { margin-top: 1.5rem; }
        .mt-4 { margin-top: 2rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }

        /* Progress bar */
        .progress-bar  { height: 6px; border-radius: 999px; background: rgba(148,163,184,.1); overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, #6366f1, #10b981); transition: width .6s ease; }

        /* Empty state */
        .empty-state {
            text-align: center; padding: 3rem 2rem;
            background: var(--card); border: 1px solid var(--border); border-radius: 1rem;
        }
        .empty-state-icon  { font-size: 2.5rem; margin-bottom: .75rem; }
        .empty-state-title { font-size: 1.05rem; font-weight: 700; margin-bottom: .4rem; }
        .empty-state-sub   { font-size: .875rem; color: var(--muted); }

        /* Session active banner */
        .session-banner {
            background: linear-gradient(135deg, rgba(99,102,241,.08), rgba(16,185,129,.05));
            border: 1px solid rgba(99,102,241,.18); border-radius: .9rem;
            padding: 1.1rem 1.4rem;
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        }

        /* Tutorial step card */
        .step-card {
            display: flex; align-items: flex-start; gap: .95rem;
            padding: 1rem 1.2rem; background: var(--card);
            border: 1px solid var(--border); border-radius: .85rem;
            color: var(--text); transition: border-color .16s, background .16s;
        }
        .step-card:hover { border-color: rgba(99,102,241,.28); background: var(--card-alt); }
        .step-card.completed { border-color: rgba(16,185,129,.2); }
        .step-num {
            width: 30px; height: 30px; border-radius: 999px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem; font-weight: 800;
            background: rgba(99,102,241,.12); color: #818cf8;
            border: 1px solid rgba(99,102,241,.2);
        }
        .step-card.completed .step-num {
            background: rgba(16,185,129,.12); color: #10b981;
            border-color: rgba(16,185,129,.2);
        }

        /* Asset card (market page) */
        .asset-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.1rem;
        }
        .asset-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 1rem; padding: 1.2rem;
            transition: border-color .18s, box-shadow .18s;
            min-width: 0; /* prevent blowout */
        }
        .asset-card:hover {
            border-color: rgba(99,102,241,.22);
            box-shadow: 0 8px 28px rgba(0,0,0,.25);
        }
        .asset-sym-badge {
            display: inline-flex; align-items: center;
            padding: .18rem .65rem; border-radius: 999px;
            font-size: .7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em;
            font-family: monospace;
            background: rgba(99,102,241,.12); color: #818cf8;
            border: 1px solid rgba(99,102,241,.18);
        }
        .asset-price-large {
            font-size: 1.7rem; font-weight: 800; font-family: monospace;
            color: var(--text); letter-spacing: -.02em;
            margin: .5rem 0 .2rem; transition: color .25s;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .asset-change {
            font-size: .75rem; font-weight: 600; font-family: monospace;
            color: var(--muted); margin-bottom: .8rem;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .asset-divider { height: 1px; background: var(--border); margin: .8rem 0; }
        .asset-trade-row {
            display: flex; align-items: center; gap: .45rem;
            width: 100%; /* fill card width */
        }
        .asset-qty-input {
            flex: 1; min-width: 0; /* critical: allows flex shrinking */
            padding: .5rem .6rem;
            background: rgba(3,7,18,.6); border: 1px solid var(--border);
            border-radius: .6rem; color: var(--text); font-size: .82rem; outline: none;
            transition: border-color .14s; width: 0; /* let flex control width */
        }
        .asset-qty-input:focus { border-color: rgba(99,102,241,.5); }
        .asset-qty-input::placeholder { color: var(--dim); }
        .trade-btn {
            flex-shrink: 0; /* never shrink the buttons */
            padding: .5rem .75rem; border-radius: .6rem;
            font-size: .76rem; font-weight: 700; cursor: pointer; border: none;
            white-space: nowrap; line-height: 1;
            transition: opacity .14s, transform .1s;
        }
        .trade-btn:hover  { opacity: .88; transform: translateY(-1px); }
        .trade-btn:active { transform: translateY(0); }
        .trade-btn-buy  { background: rgba(16,185,129,.18); color: #10b981; border: 1px solid rgba(16,185,129,.25); }
        .trade-btn-sell { background: rgba(239,68,68,.15);  color: #ef4444; border: 1px solid rgba(239,68,68,.22);  }
        .asset-info-row { display: flex; gap: 1rem; flex-wrap: wrap; }
        .ai-label { font-size: .63rem; color: var(--dim); text-transform: uppercase; letter-spacing: .06em; display: block; }
        .ai-value { font-size: .78rem; font-weight: 600; color: var(--muted); }

        /* Old classes for admin compatibility */
        .error-list {
            margin-bottom: 1rem; padding: .7rem 1rem;
            border-radius: .65rem; background: var(--red-soft);
            border: 1px solid rgba(239,68,68,.25); color: #fca5a5; font-size: .875rem;
        }
        .error-list ul { margin: 0; padding-left: 1.2rem; }
        .field { margin-bottom: .9rem; }
        .field label { display: block; font-size: .8rem; color: var(--muted); margin-bottom: .25rem; }
        .field input, .field select {
            width: 100%; padding: .55rem .75rem;
            border-radius: .6rem; border: 1px solid var(--border-md);
            background: rgba(3,7,18,.7); color: var(--text); font-size: .9rem; outline: none;
        }
        .field input:focus, .field select:focus {
            border-color: var(--accent); box-shadow: 0 0 0 2px rgba(99,102,241,.12);
        }
        .muted-text { color: var(--muted); font-size: .875rem; }
    </style>
</head>
<body>

@if(request()->routeIs('login.show') || request()->routeIs('register.show'))
    <div class="auth-shell">
        @yield('content')
    </div>
@else
    <div class="shell">

        {{-- ── Sidebar ── --}}
        <aside class="sidebar">
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">TS</div>
                <div>
                    <div class="sidebar-app-name">TradeSimulator</div>
                    <div class="sidebar-app-tag">Practice · Risk Free</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                @include('partials.sidebar')
            </nav>

            @if(session()->has('user_id'))
                <div class="sidebar-footer">
                    <div class="sf-name">{{ session('user_name') }}</div>
                    <div class="sf-role">{{ session('user_role') }}</div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sf-logout">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Sign out
                        </button>
                    </form>
                </div>
            @endif
        </aside>

        {{-- ── Main ── --}}
        <div class="main">
            <header class="topbar">
                <div class="topbar-left">
                    <div class="topbar-dot"></div>
                    <span class="topbar-label">Virtual trading sandbox · All prices simulated</span>
                </div>
                <div class="topbar-right">
                    @if(session()->has('user_id') && session('user_role') === 'user')
                        @php $topbarUser = \App\Models\User::find(session('user_id')); @endphp
                        @if($topbarUser)
                            <div class="topbar-balance">£{{ number_format($topbarUser->current_balance, 2) }} cash</div>
                        @endif
                    @endif
                    <div class="topbar-pill">{{ session('user_name', 'Trader') }}</div>
                </div>
            </header>

            <main class="content">
                @if(session('status'))
                    <div class="flash">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="15" height="15">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="flash flash-error">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="15" height="15">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>
@endif

</body>
</html>
