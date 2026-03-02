@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>User Dashboard</h1>
        <p>Welcome, {{ $name }}.</p>

        @if(isset($user))
            <h2 style="margin-top:1.5rem;">Account Summary</h2>
            <p>Initial balance: £{{ number_format($user->initial_balance, 2) }}</p>
            <p>Current cash balance: £{{ number_format($user->current_balance, 2) }}</p>
        @endif

        @if(isset($portfolio) && $portfolio)
            <h2 style="margin-top:1.5rem;">Portfolio Summary</h2>
            <p>Total invested: £{{ number_format($portfolio->total_invested, 2) }}</p>
            <p>Total profit / loss: £{{ number_format($portfolio->total_profit_loss, 2) }}</p>
        @else
            <p style="margin-top:1.5rem;">No portfolio statistics yet. Start trading from the Market page.</p>
        @endif
    </div>
@endsection

