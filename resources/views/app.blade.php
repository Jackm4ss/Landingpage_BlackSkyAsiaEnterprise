<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $defaultMeta = request()->is('discover')
            ? [
                'title' => 'Discover Events | Black Sky Enterprise',
                'description' => 'Explore Black Sky Enterprise concerts, festivals, and live entertainment events across Malaysia, Indonesia, and Southeast Asia.',
                'canonical' => url('/discover'),
                'type' => 'website',
            ]
            : [
                'title' => config('app.name', 'Black Sky'),
                'description' => 'Black Sky Enterprise is a concert promoter and entertainment platform for events, artists, and live shows in Southeast Asia.',
                'canonical' => url('/'),
                'type' => 'website',
            ];
        $meta = array_merge($defaultMeta, $pageMeta ?? []);
        $schemas = $structuredData ?? (request()->is('discover')
            ? [[
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => 'Discover Events',
                'description' => $meta['description'],
                'url' => $meta['canonical'],
            ]]
            : []);
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $meta['description'] }}">
    @if (filled($meta['keywords'] ?? null))
        <meta name="keywords" content="{{ $meta['keywords'] }}">
    @endif
    <link rel="canonical" href="{{ $meta['canonical'] }}">
    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:type" content="{{ $meta['type'] ?? 'website' }}">
    <meta property="og:url" content="{{ $meta['canonical'] }}">
    @if (filled($meta['image'] ?? null))
        <meta property="og:image" content="{{ $meta['image'] }}">
    @endif

    <title>{{ $meta['title'] }}</title>

    @foreach ($schemas as $schema)
        <script type="application/ld+json">
            {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endforeach

    @viteReactRefresh
    @vite('src/main.tsx')
</head>
<body>
    <div id="root"></div>
</body>
</html>
