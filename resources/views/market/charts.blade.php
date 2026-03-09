@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Charts</h1>
        <p class="card-subtitle">
            Select an asset to open its professional trading graph.
        </p>

        @if($assets->isEmpty())
            <p class="muted-text">No active assets found. Ask the admin to enable some assets.</p>
        @else
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:0.75rem;margin-top:1rem;">
                @foreach($assets as $asset)
                    <a href="{{ route('market.chart', $asset->id) }}"
                       style="text-decoration:none;">
                        <div style="border:1px solid #1f2937;border-radius:0.75rem;padding:1rem;background:rgba(148,163,184,0.06);">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <div>
                                    <div style="font-weight:700;color:#e5e7eb;">{{ $asset->symbol }}</div>
                                    <div style="font-size:0.85rem;color:#9ca3af;">{{ $asset->name }}</div>
                                </div>
                                <div style="font-size:0.85rem;color:#9ca3af;">
                                    {{ $asset->behaviour_profile }}
                                </div>
                            </div>
                            <div style="margin-top:0.6rem;color:#e5e7eb;">
                                Base price: £{{ number_format($asset->base_price, 2) }}
                            </div>
                            <div style="margin-top:0.25rem;color:#9ca3af;font-size:0.85rem;">
                                Volatility: {{ $asset->volatility }} · Liquidity: {{ number_format($asset->liquidity) }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection

