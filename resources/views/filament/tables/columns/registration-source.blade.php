@php
    $state = $getState();
    $meta = \App\Support\RegistrationSourceMeta::for($state);
@endphp

@if (filled($state))
    <span class="bsa-table-source">
        <x-bsa.source-icon
            :icon="$meta['icon']"
            :color="$meta['color']"
            :label="$meta['label']"
            compact
        />
        <span>{{ $meta['label'] }}</span>
    </span>
@else
    <span class="bsa-table-source bsa-table-source--empty">
        <x-heroicon-o-minus-circle />
        <span>No attribution</span>
    </span>
@endif
