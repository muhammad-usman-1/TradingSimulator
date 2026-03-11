<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login · TradeSimulator</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }

        body {
            font-family: system-ui, -apple-system, 'Segoe UI', sans-serif;
            background: #030712;
            color: #e2e8f0;
            min-height: 100vh;
        }

        /* Grid background */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(148,163,184,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148,163,184,.04) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
            z-index: 0;
        }


        /* ── Page layout ── */
        .page {
            display: flex;
            min-height: 100vh;
            position: relative; z-index: 1;
        }

        /* ── LEFT PANEL ── */
        .left {
            width: 52%;
            background: linear-gradient(155deg, #0a0f1e 0%, #060c18 60%, #030712 100%);
            border-right: 1px solid rgba(148,163,184,.06);
            padding: 2.75rem 3.5rem;
            display: flex; flex-direction: column; gap: 1.6rem;
            position: relative; overflow: hidden;
        }
        .left::before {
            content: '';
            position: absolute; top: -150px; right: -150px;
            width: 550px; height: 550px;
            background: radial-gradient(circle, rgba(16,185,129,.07) 0%, transparent 65%);
            pointer-events: none;
        }
        .left::after {
            content: '';
            position: absolute; bottom: -100px; left: -100px;
            width: 450px; height: 450px;
            background: radial-gradient(circle, rgba(99,102,241,.05) 0%, transparent 65%);
            pointer-events: none;
        }

        /* Brand */
        .brand { display: flex; align-items: center; gap: .85rem; }
        .brand-logo {
            width: 42px; height: 42px; border-radius: 11px;
            background: linear-gradient(135deg, #10b981 0%, #6366f1 100%);
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 1rem; color: #fff;
            box-shadow: 0 0 28px rgba(16,185,129,.28), 0 4px 14px rgba(0,0,0,.5);
            flex-shrink: 0;
            position: relative; z-index: 1;
        }
        .brand-name { font-size: 1.05rem; font-weight: 700; color: #e2e8f0; }
        .brand-tag { font-size: .7rem; color: #475569; margin-top: .1rem; }

        /* Hero */
        .hero-title {
            font-size: 3rem; font-weight: 800; line-height: 1.1;
            color: #f1f5f9; letter-spacing: -.03em;
        }
        .hero-grad {
            background: linear-gradient(90deg, #10b981 0%, #6366f1 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-sub {
            font-size: 1rem; color: #64748b; line-height: 1.65;
            margin-top: .65rem; max-width: 370px;
        }

        /* Chart card */
        .chart-card {
            background: rgba(10,15,30,.65);
            border: 1px solid rgba(148,163,184,.09);
            border-radius: 1rem; padding: 1rem 1.2rem .75rem;
            position: relative; overflow: hidden;
        }
        .chart-top {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: .5rem;
        }
        .chart-label { font-size: .73rem; color: #64748b; font-weight: 500; }
        .chart-badge {
            font-size: .71rem; font-weight: 700; color: #10b981;
            background: rgba(16,185,129,.1);
            border: 1px solid rgba(16,185,129,.22);
            padding: .15rem .6rem; border-radius: 999px;
        }
        .chart-svg-box { width: 100%; height: 108px; }
        .c-svg { width: 100%; height: 100%; overflow: visible; }

        /* Features */
        .features { display: flex; flex-direction: column; gap: .85rem; }
        .feat { display: flex; align-items: flex-start; gap: .8rem; }
        .feat-icon {
            width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: .82rem; font-weight: 700;
        }
        .feat-icon.g { background: rgba(16,185,129,.12); color: #10b981; border: 1px solid rgba(16,185,129,.2); }
        .feat-icon.b { background: rgba(99,102,241,.12); color: #818cf8; border: 1px solid rgba(99,102,241,.2); }
        .feat-icon.a { background: rgba(245,158,11,.12); color: #f59e0b; border: 1px solid rgba(245,158,11,.2); }
        .feat-title { font-size: .92rem; font-weight: 600; color: #e2e8f0; }
        .feat-desc { font-size: .82rem; color: #64748b; margin-top: .12rem; line-height: 1.5; }

        /* Stats row */
        .stats-row {
            display: flex; gap: 2.25rem;
            margin-top: auto; padding-top: 1.4rem;
            border-top: 1px solid rgba(148,163,184,.06);
        }
        .stat-val { font-size: 1.15rem; font-weight: 800; color: #f1f5f9; }
        .stat-lbl { font-size: .67rem; color: #475569; text-transform: uppercase; letter-spacing: .07em; margin-top: .1rem; }

        /* ── RIGHT PANEL ── */
        .right {
            flex: 1;
            display: flex; align-items: center; justify-content: center;
            padding: 2rem;
            background: #030712;
        }

        /* Form card */
        .fcard {
            width: 100%; max-width: 420px;
            background: rgba(8,13,28,.88);
            border: 1px solid rgba(148,163,184,.1);
            border-radius: 1.5rem;
            padding: 2.5rem 2.25rem;
            backdrop-filter: blur(24px);
            box-shadow: 0 30px 64px rgba(0,0,0,.55), 0 0 0 1px rgba(148,163,184,.04);
        }
        .fcard-title {
            font-size: 2rem; font-weight: 800;
            color: #f1f5f9; letter-spacing: -.03em;
        }
        .fcard-sub {
            font-size: .95rem; color: #64748b;
            margin-top: .3rem; margin-bottom: 1.85rem;
        }

        /* Alert */
        .alert-err {
            background: rgba(239,68,68,.09);
            border: 1px solid rgba(239,68,68,.27);
            border-radius: .75rem; color: #fca5a5;
            padding: .75rem 1rem; font-size: .84rem;
            margin-bottom: 1.2rem;
            display: flex; align-items: center; gap: .5rem;
        }

        /* Fields */
        .field { margin-bottom: 1.15rem; }
        .field label {
            display: block; font-size: .85rem; font-weight: 500;
            color: #94a3b8; margin-bottom: .38rem;
        }
        .iwrap { position: relative; }
        .iico {
            position: absolute; left: .85rem; top: 50%; transform: translateY(-50%);
            width: 15px; height: 15px; color: #4b5563; pointer-events: none;
        }
        .iwrap input {
            width: 100%;
            padding: .75rem .875rem .75rem 2.4rem;
            background: rgba(3,7,18,.75);
            border: 1px solid rgba(148,163,184,.11);
            border-radius: .75rem;
            color: #e2e8f0; font-size: .95rem;
            transition: border-color .18s, box-shadow .18s, background .18s;
            outline: none;
        }
        .iwrap input::placeholder { color: #374151; }
        .iwrap input:focus {
            border-color: rgba(16,185,129,.5);
            box-shadow: 0 0 0 3px rgba(16,185,129,.1);
            background: rgba(3,7,18,.95);
        }
        .pw-toggle {
            position: absolute; right: .85rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #4b5563; display: flex; padding: 2px;
            transition: color .15s;
        }
        .pw-toggle:hover { color: #94a3b8; }

        /* Submit */
        .btn-submit {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: .5rem;
            padding: .9rem 1.5rem;
            background: linear-gradient(135deg, #10b981 0%, #6366f1 100%);
            border: none; border-radius: .75rem;
            color: #fff; font-size: 1rem; font-weight: 700;
            cursor: pointer; letter-spacing: .01em;
            transition: opacity .18s, transform .12s, box-shadow .2s;
            box-shadow: 0 4px 22px rgba(16,185,129,.28);
            margin-top: .6rem;
        }
        .btn-submit:hover { opacity: .92; transform: translateY(-1px); box-shadow: 0 8px 32px rgba(16,185,129,.35); }
        .btn-submit:active { transform: translateY(0); }
        .btn-arrow { transition: transform .18s; }
        .btn-submit:hover .btn-arrow { transform: translateX(3px); }

        /* Footer */
        .ffoot {
            text-align: center; margin-top: 1.5rem;
            font-size: .84rem; color: #64748b;
        }
        .ffoot a { color: #10b981; text-decoration: none; font-weight: 600; }
        .ffoot a:hover { text-decoration: underline; }

        /* ── Responsive ── */
        @media (max-width: 820px) {
            .left { display: none; }
            .right { padding: 1.25rem; }
            .fcard { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

<div class="page">

    <!-- ── LEFT PANEL ── -->
    <div class="left">

        <div class="brand">
            <div class="brand-logo">TS</div>
            <div>
                <div class="brand-name">TradeSimulator</div>
                <div class="brand-tag">Practice · Learn · Grow</div>
            </div>
        </div>

        <div>
            <h1 class="hero-title">Master the Markets.<br><span class="hero-grad">Risk Free.</span></h1>
            <p class="hero-sub">Practice real trading strategies with $1,000 virtual funds. Learn how markets react to your decisions before you risk real capital.</p>
        </div>

        <!-- Animated Line Chart -->
        <div class="chart-card">
            <div class="chart-top">
                <span class="chart-label">Portfolio Performance · 30 days</span>
                <span class="chart-badge">▲ +12.4%</span>
            </div>
            <div class="chart-svg-box">
                <svg class="c-svg" viewBox="0 0 560 100" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="areaFill" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#10b981" stop-opacity=".22"/>
                            <stop offset="100%" stop-color="#10b981" stop-opacity="0"/>
                        </linearGradient>
                        <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
                            <feGaussianBlur in="SourceGraphic" stdDeviation="2.5" result="blur"/>
                            <feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge>
                        </filter>
                    </defs>
                    <!-- Horizontal grid -->
                    <line x1="0" y1="25" x2="560" y2="25" stroke="rgba(148,163,184,.06)" stroke-width="1"/>
                    <line x1="0" y1="50" x2="560" y2="50" stroke="rgba(148,163,184,.06)" stroke-width="1"/>
                    <line x1="0" y1="75" x2="560" y2="75" stroke="rgba(148,163,184,.06)" stroke-width="1"/>
                    <!-- Area fill -->
                    <path fill="url(#areaFill)"
                          d="M0,88 C25,82 50,86 80,76 C110,66 130,72 160,60
                             C190,48 210,55 240,42 C270,29 290,36 320,22
                             C350,8 370,15 400,7 C430,-1 450,5 485,3
                             C510,2 535,5 560,2 L560,100 L0,100 Z"/>
                    <!-- Main line (animated) -->
                    <path id="chart-path" fill="none" stroke="#10b981" stroke-width="2"
                          stroke-linecap="round" stroke-linejoin="round"
                          filter="url(#glow)"
                          d="M0,88 C25,82 50,86 80,76 C110,66 130,72 160,60
                             C190,48 210,55 240,42 C270,29 290,36 320,22
                             C350,8 370,15 400,7 C430,-1 450,5 485,3
                             C510,2 535,5 560,2"/>
                    <!-- Pulsing end dot -->
                    <circle cx="560" cy="2" r="4" fill="#10b981" filter="url(#glow)"/>
                    <circle cx="560" cy="2" r="4" fill="none" stroke="#10b981" stroke-width="1.5" opacity=".5">
                        <animate attributeName="r" values="4;10;4" dur="2.4s" repeatCount="indefinite"/>
                        <animate attributeName="opacity" values=".5;0;.5" dur="2.4s" repeatCount="indefinite"/>
                    </circle>
                </svg>
            </div>
        </div>

        <!-- Features -->
        <div class="features">
            <div class="feat">
                <div class="feat-icon g">$</div>
                <div>
                    <div class="feat-title">$1,000 Virtual Starting Balance</div>
                    <div class="feat-desc">Trade immediately — no deposit, no real money at risk</div>
                </div>
            </div>
            <div class="feat">
                <div class="feat-icon b">◈</div>
                <div>
                    <div class="feat-title">4 Live-Simulated Assets</div>
                    <div class="feat-desc">BLUE, TECH, REIT & BTC with realistic price impact mechanics</div>
                </div>
            </div>
            <div class="feat">
                <div class="feat-icon a">✦</div>
                <div>
                    <div class="feat-title">Interactive Trading Tutorials</div>
                    <div class="feat-desc">Candle reading, risk management & market strategy guides</div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-row">
            <div><div class="stat-val">$1K</div><div class="stat-lbl">Starting Balance</div></div>
            <div><div class="stat-val">4</div><div class="stat-lbl">Assets</div></div>
            <div><div class="stat-val">100%</div><div class="stat-lbl">Risk Free</div></div>
        </div>

    </div>

    <!-- ── RIGHT PANEL ── -->
    <div class="right">
        <div class="fcard">
            <h2 class="fcard-title">Welcome back</h2>
            <p class="fcard-sub">Log in to continue your practice session</p>

            @if(!empty($error ?? null))
                <div class="alert-err">
                    <svg width="15" height="15" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $error }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.perform') }}">
                @csrf

                <div class="field">
                    <label for="email">Email address</label>
                    <div class="iwrap">
                        <svg class="iico" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <input id="email" name="email" type="email"
                               placeholder="you@example.com"
                               value="{{ $old['email'] ?? '' }}"
                               autocomplete="email" required>
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="iwrap">
                        <svg class="iico" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        <input id="password" name="password" type="password"
                               placeholder="••••••••"
                               autocomplete="current-password" required>
                        <button type="button" class="pw-toggle" onclick="togglePw('password', this)" title="Toggle visibility">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <span>Log In to Dashboard</span>
                    <svg class="btn-arrow" viewBox="0 0 20 20" fill="currentColor" width="17" height="17">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </form>

            <div class="ffoot">
                New to TradeSimulator? <a href="{{ route('register.show') }}">Create free account →</a>
            </div>
        </div>
    </div>

</div>

<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.style.color = isText ? '' : '#10b981';
}

// Draw chart line on load
const path = document.getElementById('chart-path');
if (path) {
    const len = path.getTotalLength();
    path.style.strokeDasharray = len;
    path.style.strokeDashoffset = len;
    path.style.transition = 'stroke-dashoffset 2.2s cubic-bezier(.4,0,.2,1)';
    requestAnimationFrame(() => requestAnimationFrame(() => {
        path.style.strokeDashoffset = '0';
    }));
}
</script>

</body>
</html>
