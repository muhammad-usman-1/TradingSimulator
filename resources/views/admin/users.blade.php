@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Users (Admin)</h1>
        <p class="card-subtitle">Below is a simple list of all registered users, their roles, and balances.</p>

        <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:0.75rem;margin-bottom:1rem;font-size:0.85rem;color:#9ca3af;">
            <div>
                <strong>Total users:</strong><br>
                {{ $totalUsers ?? count($users) }}
            </div>
            <div>
                <strong>Total trades:</strong><br>
                {{ $totalTrades ?? 0 }}
            </div>
            <div>
                <strong>Active assets:</strong><br>
                {{ $totalAssets ?? 0 }}
            </div>
            <div>
                <strong>Combined balances:</strong><br>
                £{{ isset($totalBalance) ? number_format($totalBalance, 2) : '0.00' }}
            </div>
        </div>

        <table style="width:100%;border-collapse:collapse;margin-top:0.5rem;">
            <thead>
            <tr style="text-align:left;border-bottom:1px solid #374151;">
                <th style="padding:0.5rem;">ID</th>
                <th style="padding:0.5rem;">Name</th>
                <th style="padding:0.5rem;">Email</th>
                <th style="padding:0.5rem;">Role</th>
                <th style="padding:0.5rem;">Initial Balance</th>
                <th style="padding:0.5rem;">Current Balance</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr style="border-bottom:1px solid #111827;">
                    <td style="padding:0.5rem;">{{ $user->id }}</td>
                    <td style="padding:0.5rem;">{{ $user->name }}</td>
                    <td style="padding:0.5rem;">{{ $user->email }}</td>
                    <td style="padding:0.5rem;">{{ $user->role }}</td>
                    <td style="padding:0.5rem;">£{{ number_format($user->initial_balance, 2) }}</td>
                    <td style="padding:0.5rem;">£{{ number_format($user->current_balance, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

