@extends('layouts.app')

@section('content')
    <h1>Create your practice account</h1>
    <p class="card-subtitle">Set up a free profile to start trading with simulated money.</p>

    @if(!empty($errors ?? []))
        <div class="error-list">
            <ul>
                @foreach($errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.perform') }}">
        @csrf

        <div class="field">
            <label for="name">Name</label>
            <input id="name" name="name" type="text" value="{{ $old['name'] ?? '' }}" required>
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ $old['email'] ?? '' }}" required>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required>
        </div>

        <div style="margin-top:1.1rem;">
            <button type="submit" class="btn">
                Create account
            </button>
            <a href="{{ route('login.show') }}" class="btn-secondary-link">
                Already registered? Log in
            </a>
        </div>
    </form>

    <p class="muted-text" style="margin-top:1.4rem;">
        We never ask for real bank details. Balances inside this simulator are purely virtual so that
        beginners can build confidence before trading with actual money.
    </p>
@endsection

