<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title . ' - ' . config('app.name') ?? config('app.name') }}</title>

@php
    $appName = config('app.name');
    $appUrl = config('app.url');
    $canonical = url()->current();
    $pageTitle = isset($title) && $title ? ($title.' - '.$appName) : $appName;
    $description = $description ?? 'A modern, privacy‑minded forum for Iran';
    $image = $image ?? asset('images/screenshot.png');
    $ogType = $ogType ?? 'website';
    $robots = app()->environment('production') ? 'index,follow' : 'noindex,nofollow';
@endphp

<meta name="description" content="{{ $description }}" />
<link rel="canonical" href="{{ $canonical }}" />
<meta name="robots" content="{{ $robots }}" />
<meta name="theme-color" content="#0ea5e9" media="(prefers-color-scheme: light)" />
<meta name="theme-color" content="#0b132b" media="(prefers-color-scheme: dark)" />

<meta property="og:site_name" content="{{ $appName }}" />
<meta property="og:type" content="{{ $ogType }}" />
<meta property="og:title" content="{{ $pageTitle }}" />
<meta property="og:description" content="{{ $description }}" />
<meta property="og:url" content="{{ $canonical }}" />
<meta property="og:image" content="{{ $image }}" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $pageTitle }}" />
<meta name="twitter:description" content="{{ $description }}" />
<meta name="twitter:image" content="{{ $image }}" />

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&family=Vazirmatn:wght@100..900&display=swap" rel="stylesheet">

<style>
    body {
        font-family: "Vazirmatn", sans-serif;
    }
</style>

<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => $appName,
    'url' => $appUrl,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
