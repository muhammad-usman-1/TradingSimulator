@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>My portfolio</h1>
        <p class="card-subtitle">
            View your current holdings, recent trades, and portfolio performance.
        </p>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-top:1.5rem;">
            <div style="background:{{ $overallPnL >= 0 ? 'rgba(34,197,94,0.1)' : 'rgba(239,68,68,0.1)' }};border:1px solid {{ $overallPnL >= 0 ? 'rgba(34,197,94,0.3)' : 'rgba(239,68,68,0.3)' }};border-radius:0.6rem;padding:1.2rem;">
                <div style="font-size:0.85rem;color:#9ca3af;margin-bottom:0.4rem;">Overall Profit/Loss</div>
                <div style="font-size:1.8rem;font-weight:600;color:{{ $overallPnL >= 0 ? '#22c55e' : '#ef4444' }};margin-bottom:0.2rem;">
                    {{ $overallPnL >= 0 ? '+' : '' }}£{{ number_format($overallPnL, 2) }}
                </div>
                <div style="font-size:0.9rem;color:{{ $overallPnL >= 0 ? '#22c55e' : '#ef4444' }};">
                    {{ $overallPnL >= 0 ? '+' : '' }}{{ number_format($overallPnLPercent, 2) }}%
                </div>
                <div style="font-size:0.75rem;color:#9ca3af;margin-top:0.3rem;">From initial: £{{ number_format($user->initial_balance, 2) }}</div>
            </div>
            <div style="background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.3);border-radius:0.6rem;padding:1rem;">
                <div style="font-size:0.85rem;color:#9ca3af;margin-bottom:0.3rem;">Cash balance</div>
                <div style="font-size:1.4rem;font-weight:600;">£{{ number_format($user->current_balance, 2) }}</div>
            </div>
            <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:0.6rem;padding:1rem;">
                <div style="font-size:0.85rem;color:#9ca3af;margin-bottom:0.3rem;">Holdings value</div>
                <div style="font-size:1.4rem;font-weight:600;">£{{ number_format($totalHoldingsValue, 2) }}</div>
            </div>
            <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:0.6rem;padding:1rem;">
                <div style="font-size:0.85rem;color:#9ca3af;margin-bottom:0.3rem;">Unrealized P&L</div>
                <div style="font-size:1.4rem;font-weight:600;color:{{ $unrealizedPnL >= 0 ? '#22c55e' : '#ef4444' }};">
                    {{ $unrealizedPnL >= 0 ? '+' : '' }}£{{ number_format($unrealizedPnL, 2) }}
                </div>
            </div>
            @if(isset($realizedPnL))
                <div style="background:rgba(148,163,184,0.1);border:1px solid rgba(148,163,184,0.3);border-radius:0.6rem;padding:1rem;">
                    <div style="font-size:0.85rem;color:#9ca3af;margin-bottom:0.3rem;">Realized P&L</div>
                    <div style="font-size:1.4rem;font-weight:600;color:{{ $realizedPnL >= 0 ? '#22c55e' : '#ef4444' }};">
                        {{ $realizedPnL >= 0 ? '+' : '' }}£{{ number_format($realizedPnL, 2) }}
                    </div>
                    <div style="font-size:0.75rem;color:#9ca3af;margin-top:0.2rem;">From closed trades</div>
                </div>
            @endif
            <div style="background:rgba(148,163,184,0.1);border:1px solid rgba(148,163,184,0.3);border-radius:0.6rem;padding:1rem;">
                <div style="font-size:0.85rem;color:#9ca3af;margin-bottom:0.3rem;">Total portfolio</div>
                <div style="font-size:1.4rem;font-weight:600;">£{{ number_format($totalPortfolioValue, 2) }}</div>
            </div>
        </div>

        @if($holdings->isEmpty())
            <div style="margin-top:2rem;padding:1.5rem;background:rgba(148,163,184,0.05);border-radius:0.6rem;border:1px solid rgba(148,163,184,0.2);">
                <p style="margin:0;color:#9ca3af;">You don't have any holdings yet. Start trading from the <a href="{{ route('market.index') }}" style="color:#6366f1;">Practice market</a>.</p>
            </div>
        @else
            <h2 style="margin-top:2rem;">Current holdings</h2>
            <table style="width:100%;border-collapse:collapse;margin-top:0.8rem;font-size:0.9rem;">
                <thead>
                <tr style="text-align:left;border-bottom:1px solid #374151;">
                    <th style="padding:0.5rem;">Asset</th>
                    <th style="padding:0.5rem;">Quantity</th>
                    <th style="padding:0.5rem;">Avg cost</th>
                    <th style="padding:0.5rem;">Current price</th>
                    <th style="padding:0.5rem;">Value</th>
                    <th style="padding:0.5rem;">P&L</th>
                </tr>
                </thead>
                <tbody>
                @foreach($holdings as $holding)
                    @php
                        $asset = $holding->asset;
                        $currentPrice = (float) $asset->base_price;
                        $value = (float) $holding->quantity * $currentPrice;
                        $cost = (float) $holding->quantity * (float) $holding->average_cost;
                        $pnl = $value - $cost;
                    @endphp
                    <tr style="border-bottom:1px solid #111827;">
                        <td style="padding:0.5rem;">
                            <strong>{{ $asset->symbol }}</strong><br>
                            <span style="font-size:0.8rem;color:#9ca3af;">{{ $asset->name }}</span>
                        </td>
                        <td style="padding:0.5rem;">{{ number_format($holding->quantity, 4) }}</td>
                        <td style="padding:0.5rem;">£{{ number_format($holding->average_cost, 2) }}</td>
                        <td style="padding:0.5rem;">£{{ number_format($currentPrice, 2) }}</td>
                        <td style="padding:0.5rem;">£{{ number_format($value, 2) }}</td>
                        <td style="padding:0.5rem;color:{{ $pnl >= 0 ? '#22c55e' : '#ef4444' }};">
                            {{ $pnl >= 0 ? '+' : '' }}£{{ number_format($pnl, 2) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @if($recentTrades->isNotEmpty())
            <h2 style="margin-top:2rem;">Recent trades</h2>
            <table style="width:100%;border-collapse:collapse;margin-top:0.8rem;font-size:0.9rem;">
                <thead>
                <tr style="text-align:left;border-bottom:1px solid #374151;">
                    <th style="padding:0.5rem;">Time</th>
                    <th style="padding:0.5rem;">Asset</th>
                    <th style="padding:0.5rem;">Side</th>
                    <th style="padding:0.5rem;">Quantity</th>
                    <th style="padding:0.5rem;">Price</th>
                    <th style="padding:0.5rem;">Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($recentTrades as $trade)
                    <tr style="border-bottom:1px solid #111827;">
                        <td style="padding:0.5rem;">{{ $trade->executed_at->format('Y-m-d H:i') }}</td>
                        <td style="padding:0.5rem;">
                            <strong>{{ $trade->asset->symbol }}</strong>
                        </td>
                        <td style="padding:0.5rem;">
                            <span style="padding:0.2rem 0.5rem;border-radius:999px;font-size:0.75rem;background:{{ $trade->side === 'buy' ? 'rgba(34,197,94,0.2)' : 'rgba(239,68,68,0.2)' }};color:{{ $trade->side === 'buy' ? '#22c55e' : '#ef4444' }};">
                                {{ strtoupper($trade->side) }}
                            </span>
                        </td>
                        <td style="padding:0.5rem;">{{ number_format($trade->quantity, 4) }}</td>
                        <td style="padding:0.5rem;">£{{ number_format($trade->price, 2) }}</td>
                        <td style="padding:0.5rem;">£{{ number_format($trade->quantity * $trade->price, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
