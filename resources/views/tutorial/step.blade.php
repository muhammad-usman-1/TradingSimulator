@extends('layouts.app')

@section('content')

<div style="max-width:720px;">

    {{-- Back link --}}
    <a href="{{ route('tutorial.index') }}"
       style="display:inline-flex; align-items:center; gap:.4rem; font-size:.82rem; color:var(--muted); margin-bottom:1.25rem; transition:color .14s;">
        <svg viewBox="0 0 20 20" fill="currentColor" width="13" height="13">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        Back to Learning Path
    </a>

    {{-- Step card --}}
    <div class="card">

        {{-- Header --}}
        <div style="display:flex; align-items:center; gap:.8rem; margin-bottom:1.25rem;">
            <div style="width:38px; height:38px; border-radius:999px; flex-shrink:0;
                        background:rgba(99,102,241,.12); border:1px solid rgba(99,102,241,.2);
                        display:flex; align-items:center; justify-content:center;">
                <svg viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2" width="17" height="17">
                    <path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>
                </svg>
            </div>
            <div>
                <div style="font-size:.65rem; color:var(--dim); text-transform:uppercase; letter-spacing:.08em; font-weight:700;">
                    Learning Path · Step
                </div>
                <h1 style="font-size:1.3rem; font-weight:800; color:var(--text); letter-spacing:-.02em; margin-top:.1rem;">
                    {{ $step['title'] }}
                </h1>
            </div>
        </div>

        <div style="height:1px; background:var(--border); margin-bottom:1.25rem;"></div>

        {{-- Body content --}}
        <div style="font-size:.95rem; color:var(--text); line-height:1.8; white-space:pre-line;">{{ $step['body'] }}</div>

        {{-- Actions --}}
        <div style="margin-top:2rem; padding-top:1.25rem; border-top:1px solid var(--border);
                    display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
            <form method="POST" action="{{ route('tutorial.complete', ['step' => $stepKey]) }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Mark as Complete
                </button>
            </form>
            <a href="{{ route('tutorial.index') }}" class="btn btn-ghost">
                All Steps
            </a>
            <a href="{{ route('market.index') }}" class="btn btn-ghost" style="color:var(--green); border-color:rgba(16,185,129,.2);">
                Try it in Market →
            </a>
        </div>

    </div>

    {{-- Beginner tip --}}
    <div style="margin-top:1.1rem; background:rgba(245,158,11,.07); border:1px solid rgba(245,158,11,.14); border-radius:.8rem; padding:.85rem 1.1rem;">
        <div style="font-size:.8rem; color:var(--muted); line-height:1.55;">
            <strong style="color:var(--amber);">Beginner tip:</strong>
            After reading this step, open the
            <a href="{{ route('market.index') }}" style="color:#818cf8; font-weight:600;">Practice Market</a>
            and try it with a small virtual amount.
            Hands-on practice builds intuition much faster than reading alone.
        </div>
    </div>

</div>

@endsection
