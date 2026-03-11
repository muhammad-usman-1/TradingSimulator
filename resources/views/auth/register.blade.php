<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account · TradeSimulator</title>
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
            background: linear-gradient(155deg, #0c0a1e 0%, #08061a 60%, #030712 100%);
            border-right: 1px solid rgba(148,163,184,.06);
            padding: 2.75rem 3.5rem;
            display: flex; flex-direction: column; gap: 1.5rem;
            position: relative; overflow: hidden;
        }
        .left::before {
            content: '';
            position: absolute; top: -150px; right: -150px;
            width: 550px; height: 550px;
            background: radial-gradient(circle, rgba(99,102,241,.08) 0%, transparent 65%);
            pointer-events: none;
        }
        .left::after {
            content: '';
            position: absolute; bottom: -100px; left: -100px;
            width: 450px; height: 450px;
            background: radial-gradient(circle, rgba(245,158,11,.05) 0%, transparent 65%);
            pointer-events: none;
        }

        /* Brand */
        .brand { display: flex; align-items: center; gap: .85rem; }
        .brand-logo {
            width: 42px; height: 42px; border-radius: 11px;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 1rem; color: #fff;
            box-shadow: 0 0 28px rgba(99,102,241,.28), 0 4px 14px rgba(0,0,0,.5);
            flex-shrink: 0;
            position: relative; z-index: 1;
        }
        .brand-name { font-size: 1.05rem; font-weight: 700; color: #e2e8f0; }
        .brand-tag { font-size: .7rem; color: #475569; margin-top: .1rem; }

        /* Hero */
        .hero-title {
            font-size: 2.9rem; font-weight: 800; line-height: 1.1;
            color: #f1f5f9; letter-spacing: -.03em;
        }
        .hero-grad {
            background: linear-gradient(90deg, #6366f1 0%, #a855f7 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-sub {
            font-size: 1rem; color: #64748b; line-height: 1.65;
            margin-top: .65rem; max-width: 370px;
        }

        /* Asset grid */
        .asset-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: .75rem;
        }
        .asset-card {
            background: rgba(10,8,28,.7);
            border: 1px solid rgba(148,163,184,.09);
            border-radius: .9rem; padding: .9rem 1rem;
            display: flex; flex-direction: column; gap: .3rem;
            transition: border-color .2s, transform .15s;
            cursor: default;
        }
        .asset-card:hover {
            border-color: rgba(99,102,241,.3);
            transform: translateY(-1px);
        }
        .asset-header { display: flex; align-items: center; justify-content: space-between; }
        .asset-sym {
            font-size: .78rem; font-weight: 700; font-family: monospace;
            color: #94a3b8; letter-spacing: .04em;
        }
        .asset-dot {
            width: 7px; height: 7px; border-radius: 999px;
        }
        .asset-price {
            font-size: 1rem; font-weight: 800; color: #f1f5f9;
            font-family: monospace;
        }
        .asset-change {
            font-size: .72rem; font-weight: 600; font-family: monospace;
        }
        .asset-change.up { color: #10b981; }
        .asset-change.dn { color: #ef4444; }
        .asset-vol { font-size: .68rem; color: #475569; margin-top: .1rem; }

        /* Progress bar inside card */
        .asset-bar {
            height: 2px; border-radius: 999px;
            background: rgba(148,163,184,.08);
            margin-top: .4rem; overflow: hidden;
        }
        .asset-bar-fill {
            height: 100%; border-radius: 999px;
            animation: barGrow .8s ease-out forwards;
            transform-origin: left;
        }
        @keyframes barGrow {
            from { transform: scaleX(0); }
            to { transform: scaleX(1); }
        }

        /* Features */
        .features { display: flex; flex-direction: column; gap: .8rem; }
        .feat { display: flex; align-items: flex-start; gap: .75rem; }
        .feat-icon {
            width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem; font-weight: 700;
        }
        .feat-icon.p { background: rgba(99,102,241,.12); color: #818cf8; border: 1px solid rgba(99,102,241,.2); }
        .feat-icon.g { background: rgba(16,185,129,.12); color: #10b981; border: 1px solid rgba(16,185,129,.2); }
        .feat-icon.a { background: rgba(245,158,11,.12); color: #f59e0b; border: 1px solid rgba(245,158,11,.2); }
        .feat-title { font-size: .92rem; font-weight: 600; color: #e2e8f0; }
        .feat-desc { font-size: .82rem; color: #64748b; margin-top: .12rem; line-height: 1.5; }

        /* Stats row */
        .stats-row {
            display: flex; gap: 2.25rem;
            margin-top: auto; padding-top: 1.4rem;
            border-top: 1px solid rgba(148,163,184,.06);
        }
        .stat-val { font-size: 1.12rem; font-weight: 800; color: #f1f5f9; }
        .stat-lbl { font-size: .67rem; color: #475569; text-transform: uppercase; letter-spacing: .07em; margin-top: .1rem; }

        /* ── RIGHT PANEL ── */
        .right {
            flex: 1;
            display: flex; align-items: center; justify-content: center;
            padding: 2rem;
            background: #030712;
            overflow-y: auto;
        }

        /* Form card */
        .fcard {
            width: 100%; max-width: 440px;
            background: rgba(8,13,28,.88);
            border: 1px solid rgba(148,163,184,.1);
            border-radius: 1.5rem;
            padding: 2.5rem 2.25rem;
            backdrop-filter: blur(24px);
            box-shadow: 0 30px 64px rgba(0,0,0,.55), 0 0 0 1px rgba(148,163,184,.04);
        }
        .fcard-title {
            font-size: 1.95rem; font-weight: 800;
            color: #f1f5f9; letter-spacing: -.03em;
        }
        .fcard-sub {
            font-size: .95rem; color: #64748b;
            margin-top: .3rem; margin-bottom: 1.75rem;
        }

        /* Alert */
        .alert-err {
            background: rgba(239,68,68,.09);
            border: 1px solid rgba(239,68,68,.27);
            border-radius: .75rem; color: #fca5a5;
            padding: .75rem 1rem; font-size: .84rem;
            margin-bottom: 1.2rem;
        }
        .alert-err ul { margin: 0; padding-left: 1.15rem; }
        .alert-err li { margin-top: .2rem; }

        /* Fields */
        .field { margin-bottom: 1.1rem; }
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
            border-color: rgba(99,102,241,.55);
            box-shadow: 0 0 0 3px rgba(99,102,241,.1);
            background: rgba(3,7,18,.95);
        }
        .pw-toggle {
            position: absolute; right: .85rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #4b5563; display: flex; padding: 2px;
            transition: color .15s;
        }
        .pw-toggle:hover { color: #94a3b8; }

        /* Password strength */
        .pw-strength { margin-top: .4rem; display: flex; gap: .3rem; }
        .pw-bar {
            flex: 1; height: 3px; border-radius: 999px;
            background: rgba(148,163,184,.1);
            transition: background .3s;
        }
        .pw-bar.weak { background: #ef4444; }
        .pw-bar.fair { background: #f59e0b; }
        .pw-bar.good { background: #10b981; }
        .pw-bar.strong { background: #6366f1; }

        /* Field row (two columns) */
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }

        /* Submit */
        .btn-submit {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: .5rem;
            padding: .9rem 1.5rem;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            border: none; border-radius: .75rem;
            color: #fff; font-size: 1rem; font-weight: 700;
            cursor: pointer; letter-spacing: .01em;
            transition: opacity .18s, transform .12s, box-shadow .2s;
            box-shadow: 0 4px 22px rgba(99,102,241,.3);
            margin-top: .6rem;
        }
        .btn-submit:hover { opacity: .92; transform: translateY(-1px); box-shadow: 0 8px 32px rgba(99,102,241,.38); }
        .btn-submit:active { transform: translateY(0); }
        .btn-arrow { transition: transform .18s; }
        .btn-submit:hover .btn-arrow { transform: translateX(3px); }

        /* Footer */
        .ffoot {
            text-align: center; margin-top: 1.4rem;
            font-size: .84rem; color: #64748b;
        }
        .ffoot a { color: #818cf8; text-decoration: none; font-weight: 600; }
        .ffoot a:hover { text-decoration: underline; }

        /* Badge */
        .free-badge {
            display: inline-flex; align-items: center; gap: .4rem;
            background: rgba(16,185,129,.08);
            border: 1px solid rgba(16,185,129,.18);
            border-radius: 999px; padding: .25rem .9rem;
            font-size: .72rem; color: #10b981; font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .free-badge-dot {
            width: 6px; height: 6px; border-radius: 999px;
            background: #10b981;
            animation: blink 1.8s ease-in-out infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; } 50% { opacity: .3; }
        }

        /* ── Responsive ── */
        @media (max-width: 820px) {
            .left { display: none; }
            .right { padding: 1.25rem; }
            .fcard { padding: 2rem 1.5rem; }
            .field-row { grid-template-columns: 1fr; }
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
            <h1 class="hero-title">Start Trading<br><span class="hero-grad">Today. Free.</span></h1>
            <p class="hero-sub">Get $1,000 in virtual funds the moment you sign up. No credit card, no real money — just you and the markets.</p>
        </div>

        <!-- Asset Cards Grid -->
        <div class="asset-grid">
            <div class="asset-card">
                <div class="asset-header">
                    <span class="asset-sym">BLUE</span>
                    <span class="asset-dot" style="background:#6366f1"></span>
                </div>
                <div class="asset-price">$100.00</div>
                <div class="asset-change up">▲ +1.24%</div>
                <div class="asset-vol">1% daily volatility</div>
                <div class="asset-bar"><div class="asset-bar-fill" style="width:20%; background:#6366f1; animation-delay:.1s"></div></div>
            </div>
            <div class="asset-card">
                <div class="asset-header">
                    <span class="asset-sym">TECH</span>
                    <span class="asset-dot" style="background:#10b981"></span>
                </div>
                <div class="asset-price">$50.00</div>
                <div class="asset-change up">▲ +3.50%</div>
                <div class="asset-vol">3.5% daily volatility</div>
                <div class="asset-bar"><div class="asset-bar-fill" style="width:65%; background:#10b981; animation-delay:.2s"></div></div>
            </div>
            <div class="asset-card">
                <div class="asset-header">
                    <span class="asset-sym">REIT</span>
                    <span class="asset-dot" style="background:#f59e0b"></span>
                </div>
                <div class="asset-price">$80.00</div>
                <div class="asset-change dn">▼ −0.53%</div>
                <div class="asset-vol">1.5% daily volatility</div>
                <div class="asset-bar"><div class="asset-bar-fill" style="width:30%; background:#f59e0b; animation-delay:.3s"></div></div>
            </div>
            <div class="asset-card">
                <div class="asset-header">
                    <span class="asset-sym">BTC</span>
                    <span class="asset-dot" style="background:#ef4444"></span>
                </div>
                <div class="asset-price">$30,000</div>
                <div class="asset-change up">▲ +2.14%</div>
                <div class="asset-vol">7% daily volatility</div>
                <div class="asset-bar"><div class="asset-bar-fill" style="width:90%; background:#ef4444; animation-delay:.4s"></div></div>
            </div>
        </div>

        <!-- Features -->
        <div class="features">
            <div class="feat">
                <div class="feat-icon p">◈</div>
                <div>
                    <div class="feat-title">Realistic Price Impact</div>
                    <div class="feat-desc">Your trades actually move the market — just like the real thing</div>
                </div>
            </div>
            <div class="feat">
                <div class="feat-icon g">$</div>
                <div>
                    <div class="feat-title">Track Your Portfolio</div>
                    <div class="feat-desc">P&amp;L tracking, holdings overview, and session history</div>
                </div>
            </div>
            <div class="feat">
                <div class="feat-icon a">✦</div>
                <div>
                    <div class="feat-title">Built-in Tutorial Path</div>
                    <div class="feat-desc">From intro to candles, risk &amp; live practice — step by step</div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-row">
            <div><div class="stat-val">$1K</div><div class="stat-lbl">Free Balance</div></div>
            <div><div class="stat-val">4</div><div class="stat-lbl">Assets</div></div>
            <div><div class="stat-val">0%</div><div class="stat-lbl">Real Risk</div></div>
        </div>

    </div>

    <!-- ── RIGHT PANEL ── -->
    <div class="right">
        <div class="fcard">

            <div class="free-badge">
                <div class="free-badge-dot"></div>
                Free forever · No credit card required
            </div>

            <h2 class="fcard-title">Create your account</h2>
            <p class="fcard-sub">Set up your profile and start trading in under a minute</p>

            @if(isset($errors) && is_array($errors) && count($errors) > 0)
                <div class="alert-err">
                    <ul>
                        @foreach($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.perform') }}">
                @csrf

                <div class="field">
                    <label for="name">Full name</label>
                    <div class="iwrap">
                        <svg class="iico" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        <input id="name" name="name" type="text"
                               placeholder="Your name"
                               value="{{ $old['name'] ?? '' }}"
                               autocomplete="name" required>
                    </div>
                </div>

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

                <div class="field-row">
                    <div class="field" style="margin-bottom:0">
                        <label for="password">Password</label>
                        <div class="iwrap">
                            <svg class="iico" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            <input id="password" name="password" type="password"
                                   placeholder="••••••••"
                                   autocomplete="new-password" required
                                   oninput="updateStrength(this.value)">
                            <button type="button" class="pw-toggle" onclick="togglePw('password', this)" title="Toggle">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="15" height="15">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                        <div class="pw-strength">
                            <div class="pw-bar" id="bar1"></div>
                            <div class="pw-bar" id="bar2"></div>
                            <div class="pw-bar" id="bar3"></div>
                            <div class="pw-bar" id="bar4"></div>
                        </div>
                    </div>

                    <div class="field" style="margin-bottom:0">
                        <label for="password_confirmation">Confirm password</label>
                        <div class="iwrap">
                            <svg class="iico" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                   placeholder="••••••••"
                                   autocomplete="new-password" required>
                            <button type="button" class="pw-toggle" onclick="togglePw('password_confirmation', this)" title="Toggle">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="15" height="15">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit" style="margin-top:1.2rem">
                    <span>Create Free Account</span>
                    <svg class="btn-arrow" viewBox="0 0 20 20" fill="currentColor" width="17" height="17">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </form>

            <div class="ffoot">
                Already have an account? <a href="{{ route('login.show') }}">Log in →</a>
            </div>
        </div>
    </div>

</div>

<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.style.color = isText ? '' : '#818cf8';
}

function updateStrength(val) {
    const bars = ['bar1','bar2','bar3','bar4'].map(id => document.getElementById(id));
    bars.forEach(b => { b.className = 'pw-bar'; });
    if (!val) return;
    const score = [val.length >= 8, /[A-Z]/.test(val), /[0-9]/.test(val), /[^a-zA-Z0-9]/.test(val)]
                  .filter(Boolean).length;
    const cls = ['weak','fair','good','strong'][score - 1] || 'weak';
    for (let i = 0; i < score; i++) bars[i].classList.add(cls);
}
</script>

</body>
</html>
