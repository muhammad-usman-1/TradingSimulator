@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Learning Path</h1>
    <p class="page-sub">Four short steps to understand how trading works — from the basics to live practice</p>
</div>

@php
    $total = count($order);
    $done  = collect($order)->filter(fn($k) => isset($progress[$k]) && $progress[$k]->completed)->count();
    $pct   = $total > 0 ? round(($done / $total) * 100) : 0;
@endphp

{{-- Progress card --}}
<div class="card mb-3" style="padding:1.25rem 1.5rem;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.9rem;">
        <div>
            <div style="font-size:.92rem; font-weight:700; color:var(--text);">Your Progress</div>
            <div style="font-size:.78rem; color:var(--muted); margin-top:.2rem;">
                {{ $done }} of {{ $total }} steps completed
            </div>
        </div>
        <div style="font-size:1.6rem; font-weight:800; color:{{ $pct === 100 ? '#10b981' : '#818cf8' }}; font-family:monospace;">
            {{ $pct }}%
        </div>
    </div>
    <div class="progress-bar">
        <div class="progress-fill" style="width:{{ $pct }}%;"></div>
    </div>
    @if($pct === 100)
    <div style="margin-top:.85rem; font-size:.82rem; color:#10b981; display:flex; align-items:center; gap:.4rem;">
        <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        All steps complete — you're ready to trade with confidence!
    </div>
    @endif
</div>

{{-- Step cards --}}
<div style="display:flex; flex-direction:column; gap:.75rem;">
    @foreach($order as $index => $stepKey)
        @php
            $completed = isset($progress[$stepKey]) && $progress[$stepKey]->completed;
            $step      = $steps[$stepKey];
            $num       = $index + 1;
        @endphp

        <a href="{{ route('tutorial.step', ['step' => $stepKey]) }}"
           class="step-card {{ $completed ? 'completed' : '' }}">

            <div class="step-num">
                @if($completed)
                    <svg viewBox="0 0 20 20" fill="currentColor" width="13" height="13">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @else
                    {{ $num }}
                @endif
            </div>

            <div style="flex:1; min-width:0;">
                <div style="font-size:.9rem; font-weight:600; color:var(--text); margin-bottom:.2rem;">
                    {{ $step['title'] }}
                </div>
                @if(!empty($step['body']))
                <div style="font-size:.77rem; color:var(--muted); line-height:1.45; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">
                    {{ mb_substr(strip_tags($step['body']), 0, 110) }}{{ mb_strlen(strip_tags($step['body'])) > 110 ? '…' : '' }}
                </div>
                @endif
            </div>

            <div style="flex-shrink:0; font-size:.75rem; font-weight:600; display:flex; align-items:center; gap:.3rem;">
                @if($completed)
                    <span style="color:#10b981;">Done</span>
                @else
                    <span style="color:var(--muted);">Read →</span>
                @endif
            </div>

        </a>
    @endforeach
</div>

{{-- Tip --}}
<div style="margin-top:1.5rem; background:rgba(245,158,11,.07); border:1px solid rgba(245,158,11,.14); border-radius:.8rem; padding:.85rem 1.1rem;">
    <div style="font-size:.8rem; color:var(--muted); line-height:1.55;">
        <strong style="color:var(--amber);">Tip:</strong>
        After reading each step, practise it in the
        <a href="{{ route('market.index') }}" style="color:#818cf8; font-weight:600;">Practice Market</a>
        with a small amount. You learn faster by doing than just reading.
    </div>
</div>

@endsection
