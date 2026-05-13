<x-filament-panels::page class="bsa-dashboard-page">
    <div class="bsa-dashboard">
        <section class="bsa-hero">
            <div>
                <p class="bsa-eyebrow">Black Sky Enterprise</p>
                <h1>Analytics Dashboard</h1>
                <p class="bsa-muted">Monitor event CMS growth, content reach, and registration geography.</p>
            </div>
            <div class="bsa-hero-date">
                <span>Updated</span>
                <strong>{{ $updatedAt }}</strong>
            </div>
        </section>

        <section class="bsa-stats-grid" aria-label="Dashboard totals">
            @foreach ($stats as $stat)
                <article class="bsa-stat-card bsa-tone-{{ $stat['tone'] }}">
                    <div class="bsa-stat-icon">
                        <x-dynamic-component :component="$stat['icon']" />
                    </div>
                    <div>
                        <span>{{ $stat['label'] }}</span>
                        <strong>{{ number_format($stat['value']) }}</strong>
                    </div>
                    <em @class(['bsa-empty-badge' => $stat['delta'] === 'No data'])>{{ $stat['delta'] }}</em>
                </article>
            @endforeach
        </section>

        <section class="bsa-dashboard-grid">
            <article class="bsa-panel bsa-traffic-panel">
                <div class="bsa-panel-head">
                    <div>
                        <span>Acquisition</span>
                        <h2>Registration Traffic Sources</h2>
                    </div>
                    <em>Live</em>
                </div>

                @if ($trafficSources === [])
                    <div class="bsa-empty-state">
                        <div class="bsa-empty-icon">
                            <x-heroicon-o-chart-pie />
                        </div>
                        <strong>No registration sources yet</strong>
                        <span>Traffic attribution will appear here after registrations start coming in.</span>
                    </div>
                @else
                    <div class="bsa-traffic-body">
                        <div
                            class="bsa-donut"
                            style="--donut: {{ $trafficGradient }}"
                            role="img"
                            aria-label="Registration traffic sources donut chart"
                        >
                            <div>
                                <span>Total</span>
                                <strong>{{ number_format($trafficTotal) }}</strong>
                            </div>
                        </div>

                        <div class="bsa-source-list">
                            @foreach ($trafficSources as $source)
                                <div class="bsa-source-row">
                                    <x-bsa.source-icon
                                        :icon="$source['icon']"
                                        :color="$source['color']"
                                        :label="$source['label']"
                                    />
                                    <div>
                                        <strong>{{ $source['label'] }}</strong>
                                        <span>{{ number_format($source['value']) }} registrations</span>
                                    </div>
                                    <b>{{ $source['percentage'] }}%</b>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>

            <article class="bsa-panel bsa-country-panel">
                <div class="bsa-panel-head">
                    <div>
                        <span>Audience</span>
                        <h2>Registration Based on Top Countries</h2>
                    </div>
                    <button type="button" aria-label="Country report options">
                        <x-heroicon-o-ellipsis-vertical />
                    </button>
                </div>

                @if ($topCountries === [])
                    <div class="bsa-empty-state bsa-empty-state-compact">
                        <div class="bsa-empty-icon">
                            <x-heroicon-o-globe-alt />
                        </div>
                        <strong>No country data yet</strong>
                        <span>Top countries will appear once registration locations are available.</span>
                    </div>
                @else
                    <div class="bsa-country-list">
                        @foreach ($topCountries as $country)
                            <div class="bsa-country-row">
                                <span
                                    class="bsa-country-flag"
                                    data-bsa-country-code="{{ strtolower($country['code']) }}"
                                    role="img"
                                    aria-label="{{ $country['country'] }} flag"
                                ></span>
                                <div>
                                    <strong>{{ number_format($country['value']) }}</strong>
                                    <span>{{ $country['country'] }}</span>
                                </div>
                                <em class="bsa-trend-{{ $country['trend'] }}">{{ $country['delta'] }}</em>
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>
        </section>
    </div>
</x-filament-panels::page>
