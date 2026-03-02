@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Login</h1>

        @if(!empty($error ?? null))
            <div class="error-list">
                <ul>
                    <li>{{ $error }}</li>
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.perform') }}">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label for="email">Email</label><br>
                <input id="email" name="email" type="email" value="{{ $old['email'] ?? '' }}" required
                       style="width:100%;padding:0.5rem;border-radius:0.5rem;border:1px solid #4b5563;background:#020617;color:#e5e7eb;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label for="password">Password</label><br>
                <input id="password" name="password" type="password" required
                       style="width:100%;padding:0.5rem;border-radius:0.5rem;border:1px solid #4b5563;background:#020617;color:#e5e7eb;">
            </div>

            <button type="submit"
                    style="padding:0.6rem 1.2rem;border-radius:9999px;border:none;background:#f97316;color:#111827;font-weight:600;cursor:pointer;">
                Login
            </button>
        </form>
    </div>
@endsection

