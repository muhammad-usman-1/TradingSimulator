@if(session()->has('user_id'))

    @if(session('user_role') === 'user')

        <div class="snav-section">Trading</div>

        <a href="{{ route('dashboard') }}"
           class="snav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="snav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('market.index') }}"
           class="snav-link {{ request()->routeIs('market.index') ? 'active' : '' }}">
            <svg class="snav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/>
                <polyline points="16 7 22 7 22 13"/>
            </svg>
            Practice Market
        </a>

        <a href="{{ route('market.charts') }}"
           class="snav-link {{ (request()->routeIs('market.charts') || request()->routeIs('market.chart') || request()->routeIs('market.history')) ? 'active' : '' }}">
            <svg class="snav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="20" x2="18" y2="10"/>
                <line x1="12" y1="20" x2="12" y2="4"/>
                <line x1="6"  y1="20" x2="6"  y2="14"/>
            </svg>
            Charts
        </a>

        <a href="{{ route('portfolio.index') }}"
           class="snav-link {{ request()->routeIs('portfolio.*') ? 'active' : '' }}">
            <svg class="snav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2"/>
                <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
            </svg>
            My Portfolio
        </a>

        <div class="snav-section" style="margin-top:.4rem;">Learn</div>

        <a href="{{ route('tutorial.index') }}"
           class="snav-link {{ request()->routeIs('tutorial.*') ? 'active' : '' }}">
            <svg class="snav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>
            </svg>
            Learning Path
        </a>

        <a href="{{ route('session.summary') }}"
           class="snav-link {{ request()->routeIs('session.*') ? 'active' : '' }}">
            <svg class="snav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
            Session Summary
        </a>

    @endif

    @if(session('user_role') === 'admin')

        <div class="snav-section">My Account</div>

        <a href="{{ route('dashboard') }}"
           class="snav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="snav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            User View
        </a>

        <div class="snav-section" style="margin-top:.4rem;">Admin</div>

        <a href="{{ route('admin.dashboard') }}"
           class="snav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg class="snav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
            </svg>
            Admin Dashboard
        </a>

        <a href="{{ route('admin.users') }}"
           class="snav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <svg class="snav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                <path d="M16 3.13a4 4 0 010 7.75"/>
            </svg>
            Manage Users
        </a>

        <a href="{{ route('admin.assets.index') }}"
           class="snav-link {{ request()->routeIs('admin.assets.*') ? 'active' : '' }}">
            <svg class="snav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            Manage Assets
        </a>

    @endif

@else
    <div style="padding:.75rem .55rem; font-size:.8rem; color:var(--dim); line-height:1.55;">
        Log in to access your dashboard.
    </div>
@endif
