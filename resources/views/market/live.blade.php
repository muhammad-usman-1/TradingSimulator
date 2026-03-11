@extends('layouts.app')

@section('content')

{{-- Header --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:.75rem;">
    <div class="page-header" style="margin-bottom:0;">
        <h1 class="page-title">Practice Market</h1>
        <p class="page-sub">Live simulated prices · Buy and sell assets below</p>
    </div>
    <div style="display:flex; align-items:center; gap:.5rem;">
        <span style="width:8px; height:8px; border-radius:999px; background:var(--green); display:inline-block; animation:liveDot 2s ease-in-out infinite;"></span>
        <span style="font-size:.75rem; color:var(--muted); font-weight:600;">Live · Updates every 3s</span>
    </div>
</div>

{{-- Tip banner --}}
<div style="background:rgba(99,102,241,.07); border:1px solid rgba(99,102,241,.14); border-radius:.8rem; padding:.8rem 1.1rem; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
    <div style="font-size:.82rem; color:var(--muted);">
        <strong style="color:var(--text);">How to trade:</strong>
        Enter a quantity (e.g. <span class="text-mono" style="color:#818cf8;">1</span> or <span class="text-mono" style="color:#818cf8;">0.5</span>), then click
        <span style="color:var(--green); font-weight:700;">▲ Buy</span> to open a position or
        <span style="color:var(--red); font-weight:700;">▼ Sell</span> to close one.
        Your cash balance updates instantly.
    </div>
    <a href="{{ route('portfolio.index') }}" class="btn btn-ghost btn-sm" style="white-space:nowrap;">My Portfolio →</a>
</div>

@if($assets->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">📊</div>
        <div class="empty-state-title">No assets configured</div>
        <div class="empty-state-sub">Ask the admin to add trading assets to get started.</div>
    </div>
@else

    {{-- Asset cards grid --}}
    <div class="asset-grid">
        @foreach($assets as $asset)
        <div class="asset-card" data-asset-row="{{ $asset->id }}">

            {{-- Card top row --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.35rem;">
                <span class="asset-sym-badge">{{ $asset->symbol }}</span>
                <a href="{{ route('market.chart', $asset->id) }}"
                   class="btn btn-ghost btn-sm" style="padding:.22rem .6rem; font-size:.68rem;">
                    Chart →
                </a>
            </div>

            <div style="font-size:.8rem; color:var(--muted); margin-bottom:.5rem;">{{ $asset->name }}</div>

            {{-- Live price --}}
            <div class="asset-price-large" id="price-{{ $asset->id }}" data-price-cell="{{ $asset->id }}">
                £{{ number_format($asset->base_price, 2) }}
            </div>

            <div class="asset-change" id="change-{{ $asset->id }}">
                — Waiting for tick
            </div>

            {{-- Asset metadata --}}
            <div class="asset-info-row">
                <div>
                    <span class="ai-label">Volatility</span>
                    <span class="ai-value">{{ number_format($asset->volatility * 100, 1) }}%</span>
                </div>
                <div>
                    <span class="ai-label">Profile</span>
                    <span class="ai-value">{{ $asset->behaviour_profile }}</span>
                </div>
                @if($asset->is_crypto)
                <div>
                    <span class="ai-label">Type</span>
                    <span class="ai-value" style="color:var(--amber);">Crypto</span>
                </div>
                @endif
            </div>

            <div class="asset-divider"></div>

            {{-- Trade form --}}
            <form method="POST" action="{{ route('market.trade') }}">
                @csrf
                <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                <div class="asset-trade-row">
                    <input type="number" name="quantity" min="0.01" step="0.01"
                           placeholder="Quantity..."
                           class="asset-qty-input"
                           id="qty-{{ $asset->id }}">
                    <button type="submit" name="side" value="buy"  class="trade-btn trade-btn-buy">▲ Buy</button>
                    <button type="submit" name="side" value="sell" class="trade-btn trade-btn-sell">▼ Sell</button>
                </div>
            </form>

        </div>
        @endforeach
    </div>

    <p style="font-size:.75rem; color:var(--dim); margin-top:1.25rem; text-align:center; line-height:1.55;">
        Prices simulate real market mechanics. Large trades on low-liquidity assets move the price more —
        just like real markets. Check the <a href="{{ route('tutorial.index') }}" style="color:#818cf8;">learning path</a> to understand why.
    </p>

@endif

<style>
@keyframes liveDot {
    0%, 100% { opacity: 1; }
    50% { opacity: .25; }
}
@keyframes priceFlashUp {
    0% { color: #10b981; text-shadow: 0 0 8px rgba(16,185,129,.4); }
    100% { color: var(--text); text-shadow: none; }
}
@keyframes priceFlashDn {
    0% { color: #ef4444; text-shadow: 0 0 8px rgba(239,68,68,.4); }
    100% { color: var(--text); text-shadow: none; }
}
.flash-up { animation: priceFlashUp .65s ease-out; }
.flash-dn { animation: priceFlashDn .65s ease-out; }
</style>

<script>
(function () {
    const TICK_MS   = 3000;
    const endpoint  = "{{ route('market.tick') }}";
    const prevPrices = {};

    function fmt(v) {
        return '£' + v.toFixed(2);
    }

    function updatePrices() {
        fetch(endpoint, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
        .then(function(r) { return r.ok ? r.json() : Promise.reject(); })
        .then(function(data) {
            if (!data.prices) return;
            for (var id in data.prices) {
                if (!Object.prototype.hasOwnProperty.call(data.prices, id)) continue;
                var price = parseFloat(data.prices[id]);
                if (isNaN(price)) continue;

                var priceEl  = document.getElementById('price-'  + id);
                var changeEl = document.getElementById('change-' + id);
                var prev     = prevPrices[id];

                if (priceEl) {
                    var isUp = !prev || price >= prev;
                    priceEl.textContent = fmt(price);
                    priceEl.classList.remove('flash-up', 'flash-dn');
                    void priceEl.offsetWidth; // force reflow to restart animation
                    priceEl.classList.add(isUp ? 'flash-up' : 'flash-dn');
                }

                if (changeEl && prev) {
                    var diff = price - prev;
                    var pct  = ((diff / prev) * 100).toFixed(3);
                    var sign = diff >= 0 ? '+' : '';
                    changeEl.textContent =
                        (diff >= 0 ? '▲ ' : '▼ ') +
                        sign + pct + '%   (' + sign + '£' + Math.abs(diff).toFixed(2) + ')';
                    changeEl.style.color = diff >= 0 ? '#10b981' : '#ef4444';
                }

                prevPrices[id] = price;
            }
        })
        .catch(function() {});
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(updatePrices, TICK_MS);
        });
    } else {
        setInterval(updatePrices, TICK_MS);
    }
})();
</script>

@endsection
