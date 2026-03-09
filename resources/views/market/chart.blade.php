@extends('layouts.app')

@section('content')
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <div>
                <h1 style="margin:0;">{{ $asset->symbol }} - {{ $asset->name }}</h1>
                <p class="card-subtitle" style="margin:0.3rem 0 0 0;">
                    Professional trading graph with price line and candlesticks (manual HTML5 canvas implementation)
                </p>
            </div>
            <div id="priceInfo" style="text-align:right;">
                <div style="font-size:1.5rem;font-weight:600;" id="currentPrice">--</div>
                <div style="font-size:0.85rem;color:#9ca3af;" id="priceChange">--</div>
            </div>
        </div>

        <div style="display:flex;gap:0.5rem;margin-bottom:1rem;flex-wrap:wrap;">
            <button id="viewCandles" class="view-btn active" onclick="setViewMode('candles')">Candles</button>
            <button id="viewLine" class="view-btn" onclick="setViewMode('line')">Line Graph</button>
            <button id="viewBoth" class="view-btn" onclick="setViewMode('both')">Both</button>
        </div>

        <div style="position:relative;background:#020617;border-radius:0.75rem;border:1px solid #1f2937;padding:1rem;">
            <canvas id="tradingChart" width="1400" height="600"
                    style="width:100%;height:600px;display:block;"></canvas>
            <div id="tooltip" style="position:absolute;display:none;background:rgba(15,23,42,0.95);border:1px solid #374151;border-radius:0.5rem;padding:0.6rem;font-size:0.85rem;pointer-events:none;z-index:10;box-shadow:0 4px 12px rgba(0,0,0,0.3);">
                <div style="font-weight:600;margin-bottom:0.3rem;" id="tooltipTitle"></div>
                <div style="display:grid;grid-template-columns:auto 1fr;gap:0.3rem 0.8rem;color:#9ca3af;">
                    <span>Open:</span><span id="tooltipOpen" style="color:#e5e7eb;"></span>
                    <span>High:</span><span id="tooltipHigh" style="color:#22c55e;"></span>
                    <span>Low:</span><span id="tooltipLow" style="color:#ef4444;"></span>
                    <span>Close:</span><span id="tooltipClose" style="color:#e5e7eb;"></span>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:1rem;margin-top:1rem;font-size:0.85rem;color:#9ca3af;">
            <div>
                <span style="color:#6366f1;">●</span> Blue line = Price movement
            </div>
            <div>
                <span style="color:#22c55e;">●</span> Green candles = Price up
            </div>
            <div>
                <span style="color:#ef4444;">●</span> Red candles = Price down
            </div>
            <div>
                <span style="color:#6b7280;">●</span> Gray bars = Volume
            </div>
        </div>
    </div>

    <style>
        .view-btn {
            padding: 0.4rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #374151;
            background: #111827;
            color: #9ca3af;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .view-btn:hover {
            background: #1f2937;
            color: #e5e7eb;
        }
        .view-btn.active {
            background: #6366f1;
            color: #fff;
            border-color: #6366f1;
        }
    </style>

    <script>
        (function () {
            const assetId = {{ $asset->id }};
            const dataEndpoint = "{{ route('market.history.json', $asset->id) }}";
            let candles = [];
            let hoveredIndex = -1;
            let viewMode = 'both'; // 'candles', 'line', 'both'

            function formatPrice(value) {
                return "£" + value.toFixed(2);
            }

            function setViewMode(mode) {
                viewMode = mode;
                document.querySelectorAll('.view-btn').forEach(btn => btn.classList.remove('active'));
                document.getElementById('view' + mode.charAt(0).toUpperCase() + mode.slice(1)).classList.add('active');
                drawChart();
            }

            window.setViewMode = setViewMode;

            function fetchCandles() {
                return fetch(dataEndpoint, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    credentials: "same-origin"
                }).then(function (response) {
                    if (!response.ok) {
                        throw new Error("Failed to fetch candle data");
                    }
                    return response.json();
                });
            }

            function drawChart() {
                const canvas = document.getElementById("tradingChart");
                if (!canvas) {
                    return;
                }
                const ctx = canvas.getContext("2d");
                if (!ctx) {
                    return;
                }

                const width = canvas.width;
                const height = canvas.height;

                ctx.clearRect(0, 0, width, height);

                if (!candles || candles.length === 0) {
                    ctx.fillStyle = "#9ca3af";
                    ctx.font = "16px system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif";
                    ctx.textAlign = "center";
                    ctx.fillText("No candles yet. Start trading to generate price history.", width / 2, height / 2);
                    return;
                }

                // Calculate price range with padding
                let minPrice = candles[0].low;
                let maxPrice = candles[0].high;
                for (let i = 1; i < candles.length; i++) {
                    if (candles[i].low < minPrice) {
                        minPrice = candles[i].low;
                    }
                    if (candles[i].high > maxPrice) {
                        maxPrice = candles[i].high;
                    }
                }

                const priceRange = maxPrice - minPrice;
                const padding = priceRange * 0.1;
                minPrice -= padding;
                maxPrice += padding;

                if (minPrice === maxPrice) {
                    minPrice -= 1;
                    maxPrice += 1;
                }

                // Layout constants
                const paddingLeft = 70;
                const paddingRight = 20;
                const paddingTop = 30;
                const volumeHeight = 80;
                const paddingBottom = 50;
                const plotHeight = height - paddingTop - paddingBottom - volumeHeight;
                const plotWidth = width - paddingLeft - paddingRight;

                // Draw background
                ctx.fillStyle = "#020617";
                ctx.fillRect(0, 0, width, height);

                // Draw grid lines and price labels
                ctx.strokeStyle = "#1f2937";
                ctx.lineWidth = 1;
                ctx.font = "11px 'Courier New', monospace";
                ctx.fillStyle = "#6b7280";
                ctx.textAlign = "right";
                ctx.textBaseline = "middle";

                const gridLines = 8;
                for (let i = 0; i <= gridLines; i++) {
                    const price = minPrice + (maxPrice - minPrice) * (1 - i / gridLines);
                    const y = paddingTop + (plotHeight * i / gridLines);

                    // Grid line
                    ctx.beginPath();
                    ctx.moveTo(paddingLeft, y);
                    ctx.lineTo(paddingLeft + plotWidth, y);
                    ctx.stroke();

                    // Price label
                    ctx.fillText(formatPrice(price), paddingLeft - 10, y);
                }

                // Draw time axis
                ctx.strokeStyle = "#1f2937";
                ctx.beginPath();
                ctx.moveTo(paddingLeft, paddingTop + plotHeight);
                ctx.lineTo(paddingLeft + plotWidth, paddingTop + plotHeight);
                ctx.stroke();

                // Time labels (guard against 0/1 candle to avoid divide-by-zero)
                ctx.textAlign = "center";
                ctx.textBaseline = "top";
                const timeLabels = Math.min(10, candles.length);

                if (timeLabels === 1) {
                    const only = candles[0];
                    const x = paddingLeft + (plotWidth / 2);
                    ctx.fillText("#" + only.time_index, x, paddingTop + plotHeight + 8);
                } else {
                    for (let i = 0; i < timeLabels; i++) {
                        const ratio = i / (timeLabels - 1);
                        const index = Math.floor((candles.length - 1) * ratio);
                        const x = paddingLeft + (plotWidth * ratio);
                        const c = candles[index];
                        if (c) {
                            ctx.fillText("#" + c.time_index, x, paddingTop + plotHeight + 8);
                        }
                    }
                }

                // Helper to convert price to y coordinate
                function priceToY(price) {
                    const normalized = (price - minPrice) / (maxPrice - minPrice);
                    return paddingTop + plotHeight - (normalized * plotHeight);
                }

                // Calculate candle dimensions
                const candleCount = candles.length;
                const candleSpacing = plotWidth / Math.max(candleCount, 1);
                const bodyWidth = Math.max(6, candleSpacing * 0.6);
                const wickWidth = 1;

                // Draw price line graph (if enabled)
                if (viewMode === 'line' || viewMode === 'both') {
                    ctx.strokeStyle = "#6366f1";
                    ctx.lineWidth = 2;
                    ctx.beginPath();

                    for (let i = 0; i < candleCount; i++) {
                        const c = candles[i];
                        const xCenter = paddingLeft + candleSpacing * i + candleSpacing / 2;
                        const yClose = priceToY(c.close);

                        if (i === 0) {
                            ctx.moveTo(xCenter, yClose);
                        } else {
                            ctx.lineTo(xCenter, yClose);
                        }
                    }

                    ctx.stroke();

                    // Draw filled area under line
                    if (viewMode === 'line') {
                        ctx.lineTo(paddingLeft + candleSpacing * (candleCount - 1) + candleSpacing / 2, paddingTop + plotHeight);
                        ctx.lineTo(paddingLeft + candleSpacing / 2, paddingTop + plotHeight);
                        ctx.closePath();
                        ctx.fillStyle = "rgba(99,102,241,0.1)";
                        ctx.fill();
                    }
                }

                // Draw candlesticks (if enabled)
                if (viewMode === 'candles' || viewMode === 'both') {
                    for (let i = 0; i < candleCount; i++) {
                        const c = candles[i];
                        const xCenter = paddingLeft + candleSpacing * i + candleSpacing / 2;

                        const yHigh = priceToY(c.high);
                        const yLow = priceToY(c.low);
                        const yOpen = priceToY(c.open);
                        const yClose = priceToY(c.close);

                        const rising = c.close >= c.open;
                        const isHovered = hoveredIndex === i;

                        // Highlight hovered candle
                        if (isHovered) {
                            ctx.fillStyle = "rgba(99,102,241,0.2)";
                            ctx.fillRect(
                                xCenter - candleSpacing / 2,
                                paddingTop,
                                candleSpacing,
                                plotHeight
                            );
                        }

                        // Wick (high-low line)
                        ctx.strokeStyle = rising ? "#22c55e" : "#ef4444";
                        ctx.lineWidth = wickWidth;
                        ctx.beginPath();
                        ctx.moveTo(xCenter, yHigh);
                        ctx.lineTo(xCenter, yLow);
                        ctx.stroke();

                        // Body (open-close rectangle)
                        const bodyTop = Math.min(yOpen, yClose);
                        const bodyBottom = Math.max(yOpen, yClose);
                        const bodyHeight = Math.max(2, bodyBottom - bodyTop);

                        ctx.fillStyle = rising ? "#22c55e" : "#ef4444";
                        ctx.fillRect(
                            xCenter - bodyWidth / 2,
                            bodyTop,
                            bodyWidth,
                            bodyHeight
                        );

                        // Body outline
                        ctx.strokeStyle = rising ? "#16a34a" : "#dc2626";
                        ctx.lineWidth = 1;
                        ctx.strokeRect(
                            xCenter - bodyWidth / 2,
                            bodyTop,
                            bodyWidth,
                            bodyHeight
                        );
                    }
                }

                // Draw volume bars
                let maxVolume = 0;
                for (let i = 0; i < candles.length; i++) {
                    if (candles[i].volume > maxVolume) {
                        maxVolume = candles[i].volume;
                    }
                }
                if (maxVolume === 0) {
                    maxVolume = 1; // Avoid division by zero
                }

                const volumeYStart = paddingTop + plotHeight + 10;
                const volumeHeightAvailable = volumeHeight - 20;

                for (let i = 0; i < candleCount; i++) {
                    const c = candles[i];
                    const xCenter = paddingLeft + candleSpacing * i + candleSpacing / 2;
                    const volumeBarWidth = Math.max(2, candleSpacing * 0.7);
                    const volumeBarHeight = (c.volume / maxVolume) * volumeHeightAvailable;

                    ctx.fillStyle = "#4b5563";
                    ctx.fillRect(
                        xCenter - volumeBarWidth / 2,
                        volumeYStart + volumeHeightAvailable - volumeBarHeight,
                        volumeBarWidth,
                        volumeBarHeight
                    );
                }

                // Volume label
                ctx.fillStyle = "#6b7280";
                ctx.font = "10px 'Courier New', monospace";
                ctx.textAlign = "left";
                ctx.fillText("Volume", paddingLeft, volumeYStart - 5);

                // Draw crosshair on hover
                if (hoveredIndex >= 0 && hoveredIndex < candles.length) {
                    const c = candles[hoveredIndex];
                    const xCenter = paddingLeft + candleSpacing * hoveredIndex + candleSpacing / 2;

                    ctx.strokeStyle = "rgba(99,102,241,0.5)";
                    ctx.lineWidth = 1;
                    ctx.setLineDash([5, 5]);

                    // Vertical line
                    ctx.beginPath();
                    ctx.moveTo(xCenter, paddingTop);
                    ctx.lineTo(xCenter, paddingTop + plotHeight + volumeHeight);
                    ctx.stroke();

                    // Horizontal line at close price
                    const yClose = priceToY(c.close);
                    ctx.beginPath();
                    ctx.moveTo(paddingLeft, yClose);
                    ctx.lineTo(paddingLeft + plotWidth, yClose);
                    ctx.stroke();

                    ctx.setLineDash([]);
                }

                // Update price info
                if (candles.length > 0) {
                    const last = candles[candles.length - 1];
                    const first = candles[0];
                    const change = last.close - first.open;
                    const changePercent = ((change / first.open) * 100).toFixed(2);

                    document.getElementById("currentPrice").textContent = formatPrice(last.close);
                    const changeEl = document.getElementById("priceChange");
                    changeEl.textContent = (change >= 0 ? "+" : "") + formatPrice(change) + " (" + changePercent + "%)";
                    changeEl.style.color = change >= 0 ? "#22c55e" : "#ef4444";
                }
            }

            function showTooltip(event, index) {
                if (index < 0 || index >= candles.length) {
                    return;
                }

                const c = candles[index];
                const tooltip = document.getElementById("tooltip");
                const canvas = document.getElementById("tradingChart");
                const rect = canvas.getBoundingClientRect();

                document.getElementById("tooltipTitle").textContent = "Candle #" + c.time_index;
                document.getElementById("tooltipOpen").textContent = formatPrice(c.open);
                document.getElementById("tooltipHigh").textContent = formatPrice(c.high);
                document.getElementById("tooltipLow").textContent = formatPrice(c.low);
                document.getElementById("tooltipClose").textContent = formatPrice(c.close);

                tooltip.style.display = "block";
                tooltip.style.left = (event.clientX - rect.left + 15) + "px";
                tooltip.style.top = (event.clientY - rect.top - 10) + "px";

                if (parseInt(tooltip.style.left) + tooltip.offsetWidth > rect.width) {
                    tooltip.style.left = (event.clientX - rect.left - tooltip.offsetWidth - 15) + "px";
                }
            }

            function hideTooltip() {
                document.getElementById("tooltip").style.display = "none";
            }

            function handleMouseMove(event) {
                const canvas = document.getElementById("tradingChart");
                if (!canvas) {
                    return;
                }
                const rect = canvas.getBoundingClientRect();
                const x = event.clientX - rect.left;
                const y = event.clientY - rect.top;

                const paddingLeft = 70;
                const paddingRight = 20;
                const plotWidth = canvas.width - paddingLeft - paddingRight;
                const candleCount = candles.length;
                const candleSpacing = plotWidth / Math.max(candleCount, 1);

                if (x >= paddingLeft && x <= canvas.width - paddingRight && candles.length > 0) {
                    const index = Math.floor((x - paddingLeft) / candleSpacing);
                    if (index >= 0 && index < candles.length) {
                        hoveredIndex = index;
                        showTooltip(event, index);
                        drawChart();
                        return;
                    }
                }

                hoveredIndex = -1;
                hideTooltip();
                drawChart();
            }

            function init() {
                fetchCandles()
                    .then(function (data) {
                        candles = data.candles || [];
                        drawChart();

                        const canvas = document.getElementById("tradingChart");
                        if (canvas) {
                            canvas.addEventListener("mousemove", handleMouseMove);
                            canvas.addEventListener("mouseleave", function () {
                                hoveredIndex = -1;
                                hideTooltip();
                                drawChart();
                            });
                        }
                    })
                    .catch(function (error) {
                        const canvas = document.getElementById("tradingChart");
                        if (!canvas) {
                            return;
                        }
                        const ctx = canvas.getContext("2d");
                        if (!ctx) {
                            return;
                        }
                        ctx.fillStyle = "#ef4444";
                        ctx.font = "16px system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif";
                        ctx.textAlign = "center";
                        ctx.fillText("Error loading candle data.", canvas.width / 2, canvas.height / 2);
                    });
            }

            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", init);
            } else {
                init();
            }
        })();
    </script>
@endsection
