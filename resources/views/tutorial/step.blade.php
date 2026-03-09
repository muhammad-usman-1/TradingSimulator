@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>{{ $step['title'] }}</h1>
        <p class="card-subtitle">
            Step in the guided learning path. Read the explanation, then mark it as complete to move on.
        </p>

        <p style="margin-top:1rem;color:#e5e7eb;font-size:0.95rem;line-height:1.6;">
            {{ $step['body'] }}
        </p>

        <form method="POST" action="{{ route('tutorial.complete', ['step' => $stepKey]) }}" style="margin-top:1.5rem;">
            @csrf
            <button type="submit" class="btn">
                Mark step as complete
            </button>
            <a href="{{ route('tutorial.index') }}" class="btn-secondary-link">
                Back to learning path
            </a>
        </form>
    </div>
@endsection

