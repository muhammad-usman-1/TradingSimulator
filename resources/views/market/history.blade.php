@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Price history: {{ $asset->name }}</h1>
        <p class="card-subtitle">
            This view uses a manual <strong>merge sort</strong> to order candles by time index
            and a <strong>binary search</strong> to jump directly to a specific candle.
        </p>

        @if(empty($sortedCandles))
            <p class="muted-text">No candles have been generated yet. Start the simulator to see history here.</p>
        @else
            <form method="GET" style="margin-bottom:1rem;">
                <input type="hidden" name="asset" value="{{ $asset->id }}">
                <div class="field" style="max-width:200px;">
                    <label for="time_index">Jump to time index (binary search)</label>
                    <input id="time_index" type="number" name="time_index"
                           value="{{ $selectedIndex !== null ? $selectedIndex : '' }}">
                </div>
                <button type="submit" class="btn">Find candle</button>
            </form>

            @if($selectedCandle)
                <div style="margin-bottom:1rem;">
                    <h2>Selected candle (time index {{ $selectedCandle['time_index'] }})</h2>
                    <p class="muted-text">
                        Open: {{ $selectedCandle['open'] }},
                        High: {{ $selectedCandle['high'] }},
                        Low: {{ $selectedCandle['low'] }},
                        Close: {{ $selectedCandle['close'] }}
                    </p>
                </div>
            @elseif($selectedIndex !== null)
                <p class="muted-text">No candle found at time index {{ $selectedIndex }}.</p>
            @endif

            <table style="width:100%;border-collapse:collapse;margin-top:0.5rem;font-size:0.9rem;">
                <thead>
                <tr style="text-align:left;border-bottom:1px solid #374151;">
                    <th style="padding:0.4rem;">Index</th>
                    <th style="padding:0.4rem;">Open</th>
                    <th style="padding:0.4rem;">High</th>
                    <th style="padding:0.4rem;">Low</th>
                    <th style="padding:0.4rem;">Close</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sortedCandles as $candle)
                    <tr style="border-bottom:1px solid #111827;">
                        <td style="padding:0.4rem;">{{ $candle['time_index'] }}</td>
                        <td style="padding:0.4rem;">{{ $candle['open'] }}</td>
                        <td style="padding:0.4rem;">{{ $candle['high'] }}</td>
                        <td style="padding:0.4rem;">{{ $candle['low'] }}</td>
                        <td style="padding:0.4rem;">{{ $candle['close'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection

