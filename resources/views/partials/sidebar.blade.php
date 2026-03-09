@if(session()->has('user_id'))
    <div>
        <div class="sidebar-section-title">Overview</div>
        <ul class="sidebar-nav">
            @if(session('user_role') === 'user')
                <li class="sidebar-item">
                    <a href="{{ route('dashboard') }}"
                       class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="sidebar-icon">🏠</span>
                        <span>My dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('market.index') }}"
                       class="sidebar-link {{ request()->routeIs('market.index') ? 'active' : '' }}">
                        <span class="sidebar-icon">📈</span>
                        <span>Practice market</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('market.charts') }}"
                       class="sidebar-link {{ request()->routeIs('market.charts') || request()->routeIs('market.chart') ? 'active' : '' }}">
                        <span class="sidebar-icon">📉</span>
                        <span>Charts</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('portfolio.index') }}"
                       class="sidebar-link {{ request()->routeIs('portfolio.*') ? 'active' : '' }}">
                        <span class="sidebar-icon">💼</span>
                        <span>My portfolio</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('tutorial.index') }}"
                       class="sidebar-link {{ request()->routeIs('tutorial.*') ? 'active' : '' }}">
                        <span class="sidebar-icon">🎓</span>
                        <span>Learning path</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('session.summary') }}"
                       class="sidebar-link {{ request()->routeIs('session.*') ? 'active' : '' }}">
                        <span class="sidebar-icon">📊</span>
                        <span>Session summary</span>
                    </a>
                </li>
            @endif

            @if(session('user_role') === 'admin')
                <li class="sidebar-item">
                    <a href="{{ route('dashboard') }}"
                       class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="sidebar-icon">👤</span>
                        <span>User view</span>
                    </a>
                </li>
            @endif
        </ul>

        @if(session('user_role') === 'admin')
            <div class="sidebar-section-title" style="margin-top:1rem;">Admin</div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="sidebar-icon">📊</span>
                        <span>Admin dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.users') }}"
                       class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <span class="sidebar-icon">👥</span>
                        <span>Manage users</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.assets.index') }}"
                       class="sidebar-link {{ request()->routeIs('admin.assets.*') ? 'active' : '' }}">
                        <span class="sidebar-icon">🏢</span>
                        <span>Manage assets</span>
                    </a>
                </li>
            </ul>
        @endif
    </div>
@else
    <div class="muted-text" style="padding:1rem;">
        Welcome to the trading simulator. Please log in or register to access the dashboard.
    </div>
@endif

