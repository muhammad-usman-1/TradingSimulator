@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Welcome, {{ $name }}</h1>
        <p class="card-subtitle">
            This is your personal trading sandbox. Watch how your virtual balance responds to different trading ideas.
        </p>

        @if(isset($overallPnL))
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-top:1.5rem;">
                <div style="background:{{ $overallPnL >= 0 ? 'rgba(34,197,94,0.1)' : 'rgba(239,68,68,0.1)' }};border:1px solid {{ $overallPnL >= 0 ? 'rgba(34,197,94,0.3)' : 'rgba(239,68,68,0.3)' }};border-radius:0.6rem;padding:1.2rem;">
                    <div style="font-size:0.85rem;color:#9ca3af;margin-bottom:0.4rem;">Overall Profit/Loss</div>
                    <div style="font-size:1.8rem;font-weight:600;color:{{ $overallPnL >= 0 ? '#22c55e' : '#ef4444' }};margin-bottom:0.2rem;">
                        {{ $overallPnL >= 0 ? '+' : '' }}£{{ number_format($overallPnL, 2) }}
                    </div>
                    <div style="font-size:0.9rem;color:{{ $overallPnL >= 0 ? '#22c55e' : '#ef4444' }};">
                        {{ $overallPnL >= 0 ? '+' : '' }}{{ number_format($overallPnLPercent, 2) }}%
                    </div>
                </div>
                <div style="background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.3);border-radius:0.6rem;padding:1.2rem;">
                    <div style="font-size:0.85rem;color:#9ca3af;margin-bottom:0.4rem;">Total portfolio value</div>
                    <div style="font-size:1.8rem;font-weight:600;">£{{ number_format($totalValue, 2) }}</div>
                    <div style="font-size:0.9rem;color:#9ca3af;margin-top:0.2rem;">From initial: £{{ number_format($user->initial_balance, 2) }}</div>
                </div>
                @if(isset($unrealizedPnL))
                    <div style="background:rgba(148,163,184,0.1);border:1px solid rgba(148,163,184,0.3);border-radius:0.6rem;padding:1.2rem;">
                        <div style="font-size:0.85rem;color:#9ca3af;margin-bottom:0.4rem;">Unrealized P&L</div>
                        <div style="font-size:1.4rem;font-weight:600;color:{{ $unrealizedPnL >= 0 ? '#22c55e' : '#ef4444' }};">
                            {{ $unrealizedPnL >= 0 ? '+' : '' }}£{{ number_format($unrealizedPnL, 2) }}
                        </div>
                        <div style="font-size:0.85rem;color:#9ca3af;margin-top:0.2rem;">From current holdings</div>
                    </div>
                @endif
            </div>
        @endif

        <div style="display:grid;grid-template-columns:minmax(0,1.4fr) minmax(0,1fr);gap:1.5rem;margin-top:1.5rem;">
            <div>
                @if(isset($user))
                    <h2>Account summary</h2>
                    <p>Initial balance: £{{ number_format($user->initial_balance, 2) }}</p>
                    <p>Current cash balance: £{{ number_format($user->current_balance, 2) }}</p>
                    @if(isset($totalHoldingsValue))
                        <p>Holdings value: £{{ number_format($totalHoldingsValue, 2) }}</p>
                    @endif
                @endif

                @if(isset($holdings) && $holdings->isNotEmpty())
                    <h2 style="margin-top:1.5rem;">Current holdings</h2>
                    <ul style="margin:0;padding-left:1.2rem;color:#9ca3af;font-size:0.95rem;">
                        @foreach($holdings as $holding)
                            <li>{{ $holding->asset->symbol }}: {{ number_format($holding->quantity, 4) }} @ £{{ number_format($holding->average_cost, 2) }}</li>
                        @endforeach
                    </ul>
                    @if(isset($totalValue))
                        <p style="margin-top:0.8rem;color:#e5e7eb;">
                            <strong>Total portfolio value:</strong> £{{ number_format($totalValue, 2) }}
                        </p>
                    @endif
                @else
                    <p style="margin-top:1.5rem;color:#9ca3af;">No holdings yet. Start trading from the <a href="{{ route('market.index') }}" style="color:#6366f1;">Practice market</a>.</p>
                @endif

                @if(isset($activeSession))
                    <h2 style="margin-top:1.5rem;">Active session</h2>
                    <p style="color:#9ca3af;font-size:0.95rem;">
                        Started: {{ $activeSession->started_at->format('Y-m-d H:i') }}<br>
                        Starting balance: £{{ number_format($activeSession->starting_balance, 2) }}<br>
                        Current balance: £{{ number_format($activeSession->current_balance, 2) }}<br>
                        <a href="{{ route('session.summary') }}" style="color:#6366f1;">View session summary →</a>
                    </p>
                @endif
            </div>

            <div>
                <h2>Next steps</h2>
                <ul style="margin:0;padding-left:1.2rem;color:#9ca3af;font-size:0.95rem;">
                    <li>Start with small position sizes so you can see how prices react without blowing up your balance.</li>
                    <li>Use the <a href="{{ route('tutorial.index') }}" style="color:#e5e7eb;">learning path</a> to
                        learn how to read candlesticks and manage risk step by step.</li>
                    <li>Compare your starting and current balances after each practice session using the
                        <a href="{{ route('session.summary') }}" style="color:#e5e7eb;">session summary</a> page.</li>
                    <li>Once candles are generated, open the history view to see how merge sort and binary search
                        help you jump around in the chart data.</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

