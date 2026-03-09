@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Session summary</h1>
        <p class="card-subtitle">
            Overview of your current simulation session. Use this page after practising in the market to reflect on
            how you traded.
        </p>

        <h2 style="margin-top:1.2rem;">Balances</h2>
        <p class="muted-text">
            Starting balance: £{{ number_format($session->starting_balance, 2) }}<br>
            Current balance: £{{ number_format($session->current_balance, 2) }}<br>
            Profit / loss: £{{ number_format($profitLoss, 2) }}
        </p>

        <h2 style="margin-top:1.2rem;">Trading activity</h2>
        <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:0.75rem;font-size:0.9rem;color:#9ca3af;">
            <div>
                <strong>Total trades</strong><br>
                {{ $totalTrades }}
            </div>
            <div>
                <strong>Buys</strong><br>
                {{ $buyTrades }}
            </div>
            <div>
                <strong>Sells</strong><br>
                {{ $sellTrades }}
            </div>
            <div>
                <strong>Total quantity traded</strong><br>
                {{ $totalQuantity }}
            </div>
            <div>
                <strong>Average trade size</strong><br>
                {{ number_format($averageTradeSize, 2) }}
            </div>
            <div>
                <strong>Net cash flow from trades</strong><br>
                £{{ number_format($netCashFlow, 2) }}
            </div>
        </div>

        <p class="muted-text" style="margin-top:1.2rem;">
            Positive net cash flow and profit mean you sold assets for more cash than you spent buying them.
            If this number is negative, review which trades were too large or too risky.
        </p>

        @if(!$session->ended_at)
            <div style="margin-top:2rem;padding:1rem;background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.3);border-radius:0.6rem;">
                <p style="margin:0 0 0.8rem 0;color:#9ca3af;">This session is still active. Complete it to finalize your results.</p>
                <form method="POST" action="{{ route('session.complete') }}">
                    @csrf
                    <button type="submit" style="padding:0.5rem 1.2rem;border-radius:999px;border:none;background:#6366f1;color:#fff;font-weight:600;cursor:pointer;">
                        Complete session
                    </button>
                </form>
            </div>
        @else
            <div style="margin-top:2rem;padding:1rem;background:rgba(148,163,184,0.1);border:1px solid rgba(148,163,184,0.3);border-radius:0.6rem;">
                <p style="margin:0;color:#9ca3af;">
                    Session completed on {{ $session->ended_at->format('Y-m-d H:i') }}.
                </p>
            </div>
        @endif

        @if(isset($trades) && $trades->isNotEmpty())
            <h2 style="margin-top:2rem;">Trade history</h2>
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
                @foreach($trades as $trade)
                    <tr style="border-bottom:1px solid #111827;">
                        <td style="padding:0.5rem;">{{ $trade->executed_at->format('H:i:s') }}</td>
                        <td style="padding:0.5rem;">{{ $trade->asset->symbol }}</td>
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

