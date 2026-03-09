@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Assets (Admin)</h1>
        <p class="card-subtitle">
            Configure which companies and crypto assets are available in the simulator and how volatile they are.
        </p>

        <h2 style="margin-top:1.25rem;">Existing assets</h2>
        @if($assets->isEmpty())
            <p class="muted-text">No assets defined yet. Use the form below to add one.</p>
        @else
            <table style="width:100%;border-collapse:collapse;margin-top:0.5rem;font-size:0.9rem;">
                <thead>
                <tr style="text-align:left;border-bottom:1px solid #374151;">
                    <th style="padding:0.4rem;">Symbol</th>
                    <th style="padding:0.4rem;">Name</th>
                    <th style="padding:0.4rem;">Base price</th>
                    <th style="padding:0.4rem;">Volatility</th>
                    <th style="padding:0.4rem;">Liquidity</th>
                    <th style="padding:0.4rem;">Profile</th>
                    <th style="padding:0.4rem;">Active</th>
                    <th style="padding:0.4rem;">Edit</th>
                </tr>
                </thead>
                <tbody>
                @foreach($assets as $asset)
                    <tr style="border-bottom:1px solid #111827;">
                        <td style="padding:0.4rem;">{{ $asset->symbol }}</td>
                        <td style="padding:0.4rem;">{{ $asset->name }}</td>
                        <td style="padding:0.4rem;">£{{ number_format($asset->base_price, 2) }}</td>
                        <td style="padding:0.4rem;">{{ $asset->volatility }}</td>
                        <td style="padding:0.4rem;">{{ $asset->liquidity }}</td>
                        <td style="padding:0.4rem;">{{ $asset->behaviour_profile }}</td>
                        <td style="padding:0.4rem;">{{ $asset->is_active ? 'Yes' : 'No' }}</td>
                        <td style="padding:0.4rem;">
                            <form method="POST" action="{{ route('admin.assets.update', $asset->id) }}"
                                  style="display:flex;gap:0.3rem;flex-wrap:wrap;align-items:center;">
                                @csrf
                                @method('PUT')
                                <input type="number" name="base_price" step="0.01" min="0.01"
                                       value="{{ $asset->base_price }}"
                                       style="width:80px;padding:0.25rem 0.35rem;border-radius:0.35rem;border:1px solid #374151;background:#020617;color:#e5e7eb;font-size:0.75rem;">
                                <input type="number" name="volatility" step="0.0001" min="0"
                                       value="{{ $asset->volatility }}"
                                       style="width:70px;padding:0.25rem 0.35rem;border-radius:0.35rem;border:1px solid #374151;background:#020617;color:#e5e7eb;font-size:0.75rem;">
                                <input type="number" name="liquidity" step="1" min="1"
                                       value="{{ $asset->liquidity }}"
                                       style="width:90px;padding:0.25rem 0.35rem;border-radius:0.35rem;border:1px solid #374151;background:#020617;color:#e5e7eb;font-size:0.75rem;">
                                <input type="text" name="behaviour_profile"
                                       value="{{ $asset->behaviour_profile }}"
                                       style="width:90px;padding:0.25rem 0.35rem;border-radius:0.35rem;border:1px solid #374151;background:#020617;color:#e5e7eb;font-size:0.75rem;">
                                <label style="font-size:0.75rem;color:#9ca3af;display:flex;align-items:center;gap:0.2rem;">
                                    <input type="checkbox" name="is_active" value="1"
                                           {{ $asset->is_active ? 'checked' : '' }}>
                                    Active
                                </label>
                                <button type="submit"
                                        style="padding:0.25rem 0.6rem;border-radius:999px;border:none;background:#6366f1;color:#f9fafb;font-size:0.75rem;cursor:pointer;">
                                    Save
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        <h2 style="margin-top:1.75rem;">Add new asset</h2>
        <form method="POST" action="{{ route('admin.assets.store') }}" style="margin-top:0.75rem;">
            @csrf
            <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:0.75rem;margin-bottom:0.75rem;">
                <div class="field">
                    <label for="symbol">Symbol</label>
                    <input id="symbol" name="symbol" type="text" required>
                </div>
                <div class="field">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" required>
                </div>
                <div class="field">
                    <label for="behaviour_profile">Profile</label>
                    <input id="behaviour_profile" name="behaviour_profile" type="text" value="custom" required>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:0.75rem;margin-bottom:0.75rem;">
                <div class="field">
                    <label for="base_price">Base price</label>
                    <input id="base_price" name="base_price" type="number" min="0.01" step="0.01" required>
                </div>
                <div class="field">
                    <label for="volatility">Volatility (e.g. 0.02)</label>
                    <input id="volatility" name="volatility" type="number" min="0" step="0.0001" value="0.02" required>
                </div>
                <div class="field">
                    <label for="liquidity">Liquidity</label>
                    <input id="liquidity" name="liquidity" type="number" min="1" step="1" value="100000" required>
                </div>
            </div>

            <label style="font-size:0.8rem;color:#9ca3af;display:flex;align-items:center;gap:0.3rem;margin-bottom:0.9rem;">
                <input type="checkbox" name="is_crypto" value="1">
                Crypto asset
            </label>

            <button type="submit" class="btn">
                Create asset
            </button>
        </form>
    </div>
@endsection

