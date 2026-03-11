@extends('layouts.app')

@section('content')

{{-- Page header --}}
<div class="page-header">
    <h1 class="page-title">Welcome back, {{ $name }}</h1>
    <p class="page-sub">Your virtual trading sandbox — real strategies, zero real risk.</p>
</div>

{{-- ── Metric cards ── --}}
@if(isset($overallPnL))
<div class="metrics-grid">

    <div class="metric {{ $overallPnL >= 0 ? 'green' : 'red' }}">
        <div class="metric-label">Overall P&amp;L</div>
        <div class="metric-value {{ $overallPnL >= 0 ? 'text-green' : 'text-red' }}">
            {{ $overallPnL >= 0 ? '+' : '' }}£{{ number_format($overallPnL, 2) }}
        </div>
        <div class="metric-sub {{ $overallPnL >= 0 ? 'text-green' : 'text-red' }}">
            {{ $overallPnL >= 0 ? '▲' : '▼' }} {{ number_format(abs($overallPnLPercent ?? 0), 2) }}% from start
        </div>
    </div>

    <div class="metric blue">
        <div class="metric-label">Portfolio Value</div>
        <div class="metric-value">£{{ number_format($totalValue ?? 0, 2) }}</div>
        <div class="metric-sub text-muted">Cash + open positions</div>
    </div>

    <div class="metric">
        <div class="metric-label">Cash Available</div>
        <div class="metric-value">£{{ number_format($user->current_balance, 2) }}</div>
        <div class="metric-sub text-muted">Ready to deploy</div>
    </div>

    @if(isset($unrealizedPnL))
    <div class="metric {{ $unrealizedPnL >= 0 ? 'green' : 'red' }}">
        <div class="metric-label">Unrealized P&amp;L</div>
        <div class="metric-value {{ $unrealizedPnL >= 0 ? 'text-green' : 'text-red' }}">
            {{ $unrealizedPnL >= 0 ? '+' : '' }}£{{ number_format($unrealizedPnL, 2) }}
        </div>
        <div class="metric-sub text-muted">From open positions</div>
    </div>
    @endif

</div>
@endif

{{-- ── Two-column body ── --}}
<div style="display:grid; grid-template-columns:minmax(0,1.4fr) minmax(0,1fr); gap:1.5rem; align-items:start;">

    {{-- Left: Holdings table --}}
    <div>
        <div class="section-title">Current Holdings</div>

        @if(isset($holdings) && $holdings->isNotEmpty())
            <div class="card" style="padding:0; overflow:hidden;">
                <table class="ds-table">
                    <thead>
                        <tr>
                            <th>Asset</th>
                            <th>Qty</th>
                            <th>Avg Cost</th>
                            <th>Value</th>
                            <th>P&amp;L</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($holdings as $holding)
                            @php
                                $ha    = $holding->asset;
                                $hp    = (float) $ha->base_price;
                                $hv    = (float) $holding->quantity * $hp;
                                $hc    = (float) $holding->quantity * (float) $holding->average_cost;
                                $hpnl  = $hv - $hc;
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge badge-info">{{ $ha->symbol }}</span>
                                    <div style="font-size:.72rem; color:var(--muted); margin-top:.2rem;">{{ $ha->name }}</div>
                                </td>
                                <td class="text-mono">{{ number_format($holding->quantity, 4) }}</td>
                                <td class="text-mono">£{{ number_format($holding->average_cost, 2) }}</td>
                                <td class="text-mono" style="font-weight:600;">£{{ number_format($hv, 2) }}</td>
                                <td class="text-mono {{ $hpnl >= 0 ? 'text-green' : 'text-red' }}" style="font-weight:700;">
                                    {{ $hpnl >= 0 ? '+' : '' }}£{{ number_format($hpnl, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="text-align:right; margin-top:.6rem;">
                <a href="{{ route('portfolio.index') }}" class="btn btn-ghost btn-sm">Full portfolio →</a>
            </div>

        @else
            <div class="empty-state">
                <div class="empty-state-icon">📈</div>
                <div class="empty-state-title">No holdings yet</div>
                <div class="empty-state-sub">Place your first trade in the Practice Market to see your positions here.</div>
                <a href="{{ route('market.index') }}" class="btn btn-primary" style="margin-top:1.1rem;">
                    Open Market →
                </a>
            </div>
        @endif

        {{-- Account summary strip --}}
        @if(isset($user))
        <div style="margin-top:1.25rem; background:rgba(148,163,184,.04); border:1px solid var(--border); border-radius:.85rem; padding:.9rem 1.1rem; display:flex; gap:2rem;">
            <div>
                <div style="font-size:.67rem; text-transform:uppercase; letter-spacing:.07em; color:var(--dim); margin-bottom:.25rem;">Starting Balance</div>
                <div class="text-mono" style="font-size:.95rem; font-weight:700;">£{{ number_format($user->initial_balance, 2) }}</div>
            </div>
            <div>
                <div style="font-size:.67rem; text-transform:uppercase; letter-spacing:.07em; color:var(--dim); margin-bottom:.25rem;">Cash Now</div>
                <div class="text-mono" style="font-size:.95rem; font-weight:700;">£{{ number_format($user->current_balance, 2) }}</div>
            </div>
            @if(isset($totalHoldingsValue))
            <div>
                <div style="font-size:.67rem; text-transform:uppercase; letter-spacing:.07em; color:var(--dim); margin-bottom:.25rem;">Holdings</div>
                <div class="text-mono" style="font-size:.95rem; font-weight:700;">£{{ number_format($totalHoldingsValue, 2) }}</div>
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- Right: Session + Beginner guide --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem;">

        {{-- Active session card --}}
        @if(isset($activeSession))
        <div>
            <div class="section-title">Active Session</div>
            <div class="session-banner">
                <div>
                    <div style="font-size:.85rem; font-weight:700; color:var(--text);">Session in progress</div>
                    <div style="font-size:.75rem; color:var(--muted); margin-top:.25rem;">
                        Started {{ $activeSession->started_at->format('H:i') }} ·
                        Balance: <span class="text-mono" style="color:var(--text);">£{{ number_format($activeSession->current_balance, 2) }}</span>
                    </div>
                </div>
                <a href="{{ route('session.summary') }}" class="btn btn-ghost btn-sm">View Summary →</a>
            </div>
        </div>
        @endif

        {{-- Step-by-step beginner guide --}}
        <div>
            <div class="section-title">Quick Start — 4 Steps</div>
            <div class="card" style="padding:1.25rem;">
                @php
                    $guides = [
                        ['n'=>'1','title'=>'Watch live prices','desc'=>'Open the Practice Market and see BLUE, TECH, REIT & BTC prices update every 3 seconds.','link'=>route('market.index'),'cta'=>'Open Market'],
                        ['n'=>'2','title'=>'Place your first trade','desc'=>'Type a small quantity like 1 in the quantity box and click Buy. Watch your cash balance drop.','link'=>route('market.index'),'cta'=>'Trade Now'],
                        ['n'=>'3','title'=>'Follow the learning path','desc'=>'Read the 4 short tutorials on candles, risk and strategy. Takes under 10 minutes total.','link'=>route('tutorial.index'),'cta'=>'Start Learning'],
                        ['n'=>'4','title'=>'Review your session','desc'=>'Check your P&L, see every trade you made, then complete the session to start fresh.','link'=>route('session.summary'),'cta'=>'View Session'],
                    ];
                @endphp
                <div style="display:flex; flex-direction:column; gap:1rem;">
                    @foreach($guides as $g)
                    <div style="display:flex; align-items:flex-start; gap:.85rem;">
                        <div style="width:26px; height:26px; border-radius:999px; background:rgba(99,102,241,.12); border:1px solid rgba(99,102,241,.2); display:flex; align-items:center; justify-content:center; font-size:.7rem; font-weight:800; color:#818cf8; flex-shrink:0; margin-top:.1rem;">{{ $g['n'] }}</div>
                        <div style="flex:1; min-width:0;">
                            <div style="font-size:.84rem; font-weight:600; color:var(--text);">{{ $g['title'] }}</div>
                            <div style="font-size:.74rem; color:var(--muted); margin-top:.15rem; line-height:1.45;">{{ $g['desc'] }}</div>
                        </div>
                        <a href="{{ $g['link'] }}" style="font-size:.72rem; color:#818cf8; white-space:nowrap; margin-top:.15rem; font-weight:600; flex-shrink:0;">{{ $g['cta'] }} →</a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
