@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">My Portfolio</h1>
    <p class="page-sub">Track your holdings, P&amp;L, and trade history</p>
</div>

{{-- Metric cards --}}
<div class="metrics-grid">

    <div class="metric {{ $overallPnL >= 0 ? 'green' : 'red' }}">
        <div class="metric-label">Overall P&amp;L</div>
        <div class="metric-value {{ $overallPnL >= 0 ? 'text-green' : 'text-red' }}">
            {{ $overallPnL >= 0 ? '+' : '' }}£{{ number_format($overallPnL, 2) }}
        </div>
        <div class="metric-sub {{ $overallPnL >= 0 ? 'text-green' : 'text-red' }}">
            {{ $overallPnL >= 0 ? '▲' : '▼' }} {{ number_format(abs($overallPnLPercent), 2) }}%
        </div>
    </div>

    <div class="metric blue">
        <div class="metric-label">Cash Balance</div>
        <div class="metric-value">£{{ number_format($user->current_balance, 2) }}</div>
        <div class="metric-sub text-muted">Available to trade</div>
    </div>

    <div class="metric">
        <div class="metric-label">Holdings Value</div>
        <div class="metric-value">£{{ number_format($totalHoldingsValue, 2) }}</div>
        <div class="metric-sub text-muted">Open positions</div>
    </div>

    <div class="metric {{ $unrealizedPnL >= 0 ? 'green' : 'red' }}">
        <div class="metric-label">Unrealized P&amp;L</div>
        <div class="metric-value {{ $unrealizedPnL >= 0 ? 'text-green' : 'text-red' }}">
            {{ $unrealizedPnL >= 0 ? '+' : '' }}£{{ number_format($unrealizedPnL, 2) }}
        </div>
        <div class="metric-sub text-muted">Unbooked gain/loss</div>
    </div>

    @if(isset($realizedPnL))
    <div class="metric {{ $realizedPnL >= 0 ? 'green' : 'red' }}">
        <div class="metric-label">Realized P&amp;L</div>
        <div class="metric-value {{ $realizedPnL >= 0 ? 'text-green' : 'text-red' }}">
            {{ $realizedPnL >= 0 ? '+' : '' }}£{{ number_format($realizedPnL, 2) }}
        </div>
        <div class="metric-sub text-muted">Closed trades</div>
    </div>
    @endif

    <div class="metric">
        <div class="metric-label">Total Portfolio</div>
        <div class="metric-value">£{{ number_format($totalPortfolioValue, 2) }}</div>
        <div class="metric-sub text-muted">Cash + holdings</div>
    </div>

</div>

{{-- Holdings --}}
<div class="section-title mt-3">Current Holdings</div>

@if($holdings->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">💼</div>
        <div class="empty-state-title">No open positions</div>
        <div class="empty-state-sub">You don't hold any assets yet. Head to the market to place your first trade.</div>
        <a href="{{ route('market.index') }}" class="btn btn-primary" style="margin-top:1.1rem;">Go to Market →</a>
    </div>
@else
    <div class="card" style="padding:0; overflow:hidden;">
        <table class="ds-table">
            <thead>
                <tr>
                    <th>Asset</th>
                    <th>Quantity</th>
                    <th>Avg Cost</th>
                    <th>Current Price</th>
                    <th>Market Value</th>
                    <th>P&amp;L</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($holdings as $holding)
                    @php
                        $ha   = $holding->asset;
                        $hp   = (float) $ha->base_price;
                        $hv   = (float) $holding->quantity * $hp;
                        $hc   = (float) $holding->quantity * (float) $holding->average_cost;
                        $hpnl = $hv - $hc;
                        $hpct = $hc > 0 ? (($hpnl / $hc) * 100) : 0;
                    @endphp
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:.55rem;">
                                <span class="badge badge-info">{{ $ha->symbol }}</span>
                                <span style="font-size:.78rem; color:var(--muted);">{{ $ha->name }}</span>
                            </div>
                        </td>
                        <td class="text-mono">{{ number_format($holding->quantity, 4) }}</td>
                        <td class="text-mono">£{{ number_format($holding->average_cost, 2) }}</td>
                        <td class="text-mono">£{{ number_format($hp, 2) }}</td>
                        <td class="text-mono" style="font-weight:700;">£{{ number_format($hv, 2) }}</td>
                        <td>
                            <div class="text-mono {{ $hpnl >= 0 ? 'text-green' : 'text-red' }}" style="font-weight:700;">
                                {{ $hpnl >= 0 ? '+' : '' }}£{{ number_format($hpnl, 2) }}
                            </div>
                            <div style="font-size:.7rem; {{ $hpnl >= 0 ? 'color:#10b981;' : 'color:#ef4444;' }}">
                                {{ $hpnl >= 0 ? '▲' : '▼' }} {{ number_format(abs($hpct), 2) }}%
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('market.index') }}" class="btn btn-ghost btn-sm">Trade</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

{{-- Recent trades --}}
@if($recentTrades->isNotEmpty())
<div class="section-title mt-4">Recent Trades</div>
<div class="card" style="padding:0; overflow:hidden;">
    <table class="ds-table">
        <thead>
            <tr>
                <th>Time</th>
                <th>Asset</th>
                <th>Side</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentTrades as $trade)
            <tr>
                <td class="text-mono" style="color:var(--muted);">{{ $trade->executed_at->format('d/m H:i') }}</td>
                <td><span class="badge badge-info">{{ $trade->asset->symbol }}</span></td>
                <td>
                    <span class="badge {{ $trade->side === 'buy' ? 'badge-buy' : 'badge-sell' }}">
                        {{ strtoupper($trade->side) }}
                    </span>
                </td>
                <td class="text-mono">{{ number_format($trade->quantity, 4) }}</td>
                <td class="text-mono">£{{ number_format($trade->price, 2) }}</td>
                <td class="text-mono" style="font-weight:700;">£{{ number_format($trade->quantity * $trade->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection
