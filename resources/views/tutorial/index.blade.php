@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Learning path</h1>
        <p class="card-subtitle">
            Follow these short steps to understand how this simulator works and how to avoid the most common beginner
            mistakes in trading.
        </p>

        <ol style="margin-top:1rem;padding-left:1.2rem;">
            @foreach($order as $stepKey)
                @php
                    $completed = isset($progress[$stepKey]) && $progress[$stepKey]->completed;
                    $step = $steps[$stepKey];
                @endphp
                <li style="margin-bottom:0.6rem;">
                    <a href="{{ route('tutorial.step', ['step' => $stepKey]) }}"
                       style="color:#e5e7eb;text-decoration:none;">
                        {{ $step['title'] }}
                    </a>
                    @if($completed)
                        <span style="font-size:0.8rem;color:#22c55e;margin-left:0.4rem;">(completed)</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
@endsection

