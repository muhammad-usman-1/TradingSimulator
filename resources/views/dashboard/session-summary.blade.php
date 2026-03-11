@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Session Summary</h1>
    <p class="page-sub">Review your trading performance — reflect before you trade again</p>
</div>

{{-- Balance overview --}}
<div class="metrics-grid" style="grid-template-columns: repeat(3, minmax(160px, 1fr));">

    <div class="metric">
        <div class="metric-label">Starting Balance</div>
        <div class="metric-value">£{{ number_format($session->starting_balance, 2) }}</div>
        <div class="metric-sub text-muted">Session start</div>
    </div>

    <div class="metric">
        <div class="metric-label">Current Balance</div>
        <div class="metric-value">£{{ number_format($session->current_balance, 2) }}</div>
        <div class="metric-sub text-muted">Cash remaining</div>
    </div>

    @php $pct = $session->starting_balance > 0 ? (($profitLoss / $session->starting_balance) * 100) : 0; @endphp
    <div class="metric {{ $profitLoss >= 0 ? 'green' : 'red' }}">
        <div class="metric-label">Session P&amp;L</div>
        <div class="metric-value {{ $profitLoss >= 0 ? 'text-green' : 'text-red' }}">
            {{ $profitLoss >= 0 ? '+' : '' }}£{{ number_format($profitLoss, 2) }}
        </div>
        <div class="metric-sub {{ $profitLoss >= 0 ? 'text-green' : 'text-red' }}">
            {{ $profitLoss >= 0 ? '▲' : '▼' }} {{ number_format(abs($pct), 2) }}%
        </div>
    </div>

</div>

{{-- Trading activity --}}
<div class="section-title mt-3">Trading Activity</div>
<div class="metrics-grid">

    <div class="metric">
        <div class="metric-label">Total Trades</div>
        <div class="metric-value text-accent">{{ $totalTrades }}</div>
        <div class="metric-sub text-muted">Buy + Sell orders</div>
    </div>

    <div class="metric green">
        <div class="metric-label">Buy Orders</div>
        <div class="metric-value text-green">{{ $buyTrades }}</div>
        <div class="metric-sub text-muted">Positions opened</div>
    </div>

    <div class="metric red">
        <div class="metric-label">Sell Orders</div>
        <div class="metric-value text-red">{{ $sellTrades }}</div>
        <div class="metric-sub text-muted">Positions closed</div>
    </div>

    <div class="metric">
        <div class="metric-label">Avg Trade Size</div>
        <div class="metric-value">{{ number_format($averageTradeSize, 2) }}</div>
        <div class="metric-sub text-muted">units per order</div>
    </div>

    <div class="metric {{ $netCashFlow >= 0 ? 'green' : 'red' }}">
        <div class="metric-label">Net Cash Flow</div>
        <div class="metric-value {{ $netCashFlow >= 0 ? 'text-green' : 'text-red' }}">
            {{ $netCashFlow >= 0 ? '+' : '' }}£{{ number_format($netCashFlow, 2) }}
        </div>
        <div class="metric-sub text-muted">Cash from all trades</div>
    </div>

    <div class="metric">
        <div class="metric-label">Vol. Traded</div>
        <div class="metric-value">{{ number_format($totalQuantity, 2) }}</div>
        <div class="metric-sub text-muted">total units</div>
    </div>

</div>

{{-- Learning note --}}
<div style="background:rgba(99,102,241,.07); border:1px solid rgba(99,102,241,.13); border-radius:.8rem; padding:.85rem 1.1rem; margin-bottom:1.5rem;">
    <div style="font-size:.82rem; color:var(--muted); line-height:1.6;">
        <strong style="color:var(--text);">What does this mean?</strong>
        A positive net cash flow means you received more cash from selling than you spent buying.
        If your P&amp;L is negative, review which trades were too large or too early — that's how real traders learn.
    </div>
</div>

{{-- Complete / completed status --}}
@if(!$session->ended_at)
    <div class="session-banner" style="margin-bottom:1.5rem;">
        <div>
            <div style="font-size:.9rem; font-weight:700; color:var(--text);">Session is still active</div>
            <div style="font-size:.76rem; color:var(--muted); margin-top:.25rem;">
                Complete it when you're done to lock in your results and start fresh.
            </div>
        </div>
        <form method="POST" action="{{ route('session.complete') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Complete Session
            </button>
        </form>
    </div>
@else
    <div style="background:rgba(16,185,129,.07); border:1px solid rgba(16,185,129,.18); border-radius:.8rem; padding:.9rem 1.1rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:.75rem;">
        <svg viewBox="0 0 20 20" fill="currentColor" width="17" height="17" style="color:#10b981; flex-shrink:0;">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span style="font-size:.85rem; color:var(--muted);">
            Session completed on <strong style="color:var(--text);">{{ $session->ended_at->format('d M Y · H:i') }}</strong>
        </span>
    </div>
@endif

{{-- Trade history --}}
@if(isset($trades) && $trades->isNotEmpty())
<div class="section-title">Trade History</div>
<div class="card" style="padding:0; overflow:hidden;">
    <table class="ds-table">
        <thead>
            <tr>
                <th>Time</th>
                <th>Asset</th>
                <th>Side</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trades as $trade)
            <tr>
                <td class="text-mono" style="color:var(--muted);">{{ $trade->executed_at->format('H:i:s') }}</td>
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
