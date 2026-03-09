@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Admin control panel</h1>
        <p class="card-subtitle">Welcome, {{ $name }}. From here you can monitor learners and tune the simulator.</p>

        <div style="display:grid;grid-template-columns:minmax(0,1.2fr) minmax(0,1fr);gap:1.5rem;margin-top:1.5rem;">
            <div>
                <h2>What you can manage</h2>
                <ul style="margin:0;padding-left:1.2rem;color:#9ca3af;font-size:0.95rem;">
                    <li>Use <strong>Manage Users</strong> in the sidebar to review learner accounts and balances.</li>
                    <li>Later you will configure simulated assets, volatility profiles, and tutorial scenarios here.</li>
                    <li>This view will also surface high-level analytics about how beginners perform in the simulator.</li>
                </ul>
            </div>

            <div>
                <h2>System status</h2>
                <p style="color:#9ca3af;font-size:0.95rem;">
                    The trading engine and database schema are being built to demonstrate complex algorithms
                    (price simulation, candles, and data structures). As you expand the project, this panel can
                    show real-time metrics such as total simulated trades, most-used assets, and learner progress.
                </p>
            </div>
        </div>
    </div>
@endsection

