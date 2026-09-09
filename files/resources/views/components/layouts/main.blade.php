@props(['title' => 'Home', 'description' => '', 'current' => '/'])
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }} · {{ $site->name }}</title>
    <meta name="description" content="{{ $description }}">

    <link rel="icon" href="/favicon.svg" type="image/svg+xml">

    <!-- Source Serif carries the headlines, Inter the text and UI, Geist Mono the model ids, prices and code. -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,400;8..60,500&family=Inter:wght@400;500;600&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Loads Tailwind, then the tokens, the type scale, the prose rules and the motion system -->
    @vite(['resources/css/site.css'])

    <!-- Flags JavaScript before first paint so scroll reveals never flash -->
    <script>document.documentElement.classList.add('js')</script>
    <script src="/js/main.js" defer></script>
</head>
<body data-instant-navigation class="min-h-dvh bg-canvas font-sans text-ink antialiased">
<!--
    One cool, smooth canvas from the first pixel to the last. The header opens
    every page the same way; the footer closes it with the status dot.
-->

    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:rounded-lg focus:bg-ink focus:px-4 focus:py-2 focus:text-canvas">Skip to content</a>

    <x-nav :links="$site->nav_links" :current="$current" />

    <main id="main" class="isolate">
        {{ $slot }}
    </main>

    <x-footer :columns="$site->footer_columns" />

</body>
</html>
