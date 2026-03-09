@extends('layouts.app')

@section('content')
    <h1>Welcome back</h1>
    <p class="card-subtitle">Log in to continue your trading practice session.</p>

    @if(!empty($error ?? null))
        <div class="error-list">
            <ul>
                <li>{{ $error }}</li>
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.perform') }}">
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ $old['email'] ?? '' }}" required>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
        </div>

        <div style="margin-top:1.1rem;">
            <button type="submit" class="btn">
                Log in
            </button>
            <a href="{{ route('register.show') }}" class="btn-secondary-link">
                New here? Create an account
            </a>
        </div>
    </form>

    <p class="muted-text" style="margin-top:1.4rem;">
        This simulator uses virtual money only, so you can safely experiment and learn how prices react
        to your trades before touching real markets.
    </p>
@endsection

