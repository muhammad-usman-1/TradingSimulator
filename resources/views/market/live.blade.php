@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Practice market</h1>
        <p class="card-subtitle">
            Watch live simulated prices for each asset. A timer calls the price engine every few seconds using
            JavaScript, without any external libraries.
        </p>

        @if($assets->isEmpty())
            <p class="muted-text">No assets are configured yet. Ask the admin to add some assets.</p>
        @else
            <table style="width:100%;border-collapse:collapse;margin-top:1rem;font-size:0.9rem;">
                <thead>
                <tr style="text-align:left;border-bottom:1px solid #374151;">
                    <th style="padding:0.4rem;">Symbol</th>
                    <th style="padding:0.4rem;">Name</th>
                    <th style="padding:0.4rem;">Current simulated price</th>
                    <th style="padding:0.4rem;">Profile</th>
                    <th style="padding:0.4rem;">Quick trade</th>
                </tr>
                </thead>
                <tbody>
                @foreach($assets as $asset)
                    <tr style="border-bottom:1px solid #111827;"
                        data-asset-row="{{ $asset->id }}">
                        <td style="padding:0.4rem;">
                            <a href="{{ route('market.chart', $asset->id) }}" style="color:#e5e7eb;text-decoration:none;">
                                {{ $asset->symbol }}
                            </a>
                        </td>
                        <td style="padding:0.4rem;">{{ $asset->name }}</td>
                        <td style="padding:0.4rem;" data-price-cell="{{ $asset->id }}">
                            £{{ number_format($asset->base_price, 2) }}
                        </td>
                        <td style="padding:0.4rem;">
                            {{ $asset->behaviour_profile }}
                        </td>
                        <td style="padding:0.4rem;">
                            <form method="POST" action="{{ route('market.trade') }}" style="display:flex;gap:0.35rem;align-items:center;">
                                @csrf
                                <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                                <input type="number" name="quantity" min="0.01" step="0.01"
                                       placeholder="Qty"
                                       style="width:70px;padding:0.25rem 0.35rem;border-radius:0.35rem;border:1px solid #374151;background:#020617;color:#e5e7eb;font-size:0.8rem;">
                                <button type="submit" name="side" value="buy"
                                        style="padding:0.25rem 0.55rem;border-radius:999px;border:none;background:#22c55e;color:#020617;font-size:0.75rem;cursor:pointer;">
                                    Buy
                                </button>
                                <button type="submit" name="side" value="sell"
                                        style="padding:0.25rem 0.55rem;border-radius:999px;border:none;background:#ef4444;color:#f9fafb;font-size:0.75rem;cursor:pointer;">
                                    Sell
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <p class="muted-text" style="margin-top:0.8rem;">
                Prices are simulated using volatility and liquidity values from the asset table. Larger trades
                (once wired in) will move thin markets more than thick ones.
            </p>
        @endif
    </div>

    <script>
        (function () {
            const TICK_INTERVAL_MS = 3000; // 3 seconds
            const endpoint = "{{ route('market.tick') }}";

            function formatPrice(value) {
                // Simple manual formatting: two decimal places, no external libraries.
                const fixed = value.toFixed(2);
                return "£" + fixed;
            }

            function updatePrices() {
                fetch(endpoint, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    credentials: "same-origin"
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error("Network response was not ok");
                        }
                        return response.json();
                    })
                    .then(function (data) {
                        if (!data.prices) {
                            return;
                        }

                        for (const assetId in data.prices) {
                            if (!Object.prototype.hasOwnProperty.call(data.prices, assetId)) {
                                continue;
                            }
                            const cell = document.querySelector('[data-price-cell="' + assetId + '"]');
                            if (cell) {
                                const price = parseFloat(data.prices[assetId]);
                                if (!isNaN(price)) {
                                    cell.textContent = formatPrice(price);
                                }
                            }
                        }
                    })
                    .catch(function () {
                        // Silent failure; in a real system we might surface a message.
                    });
            }

            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", function () {
                    setInterval(updatePrices, TICK_INTERVAL_MS);
                });
            } else {
                setInterval(updatePrices, TICK_INTERVAL_MS);
            }
        })();
    </script>
@endsection

