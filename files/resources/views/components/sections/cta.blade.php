@props([
    'heading' => 'Point your client at Prism. Keep everything else.',
    'body' => 'Free for a million routed tokens a month. Provider prices at list, on one invoice.',
    'ctaText' => 'Get an API key',
    'ctaLink' => '/docs/quickstart',
    'showSecondary' => '1',
    'secondaryText' => 'See pricing',
    'secondaryLink' => '/pricing',
])
<!--
    The closing call: the light returns from the hero, four wide ripples of it,
    gathered into the top-right corner so the centred serif heading, the line
    under it and the two buttons keep the room to themselves.
-->
<section class="relative overflow-hidden py-24 md:py-32">
    <div class="beams beams-corner" aria-hidden="true"><canvas data-iridescence data-flutes="4"></canvas></div>
    <div class="relative z-10 mx-auto flex max-w-page flex-col items-center px-6 text-center" data-reveal>
        <h2 class="display-section max-w-[24ch] font-display text-balance text-ink">{{ $heading }}</h2>
        <p class="lede mt-5 max-w-[46ch] text-pretty text-muted">{{ $body }}</p>
        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ $ctaLink }}" class="btn-solid btn-lg">
                {{ $ctaText }}
                <svg viewBox="0 0 16 16" class="arrow size-4 shrink-0 fill-current" aria-hidden="true">
                    <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 0 1-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                </svg>
            </a>
            @if ($showSecondary)
            <a href="{{ $secondaryLink }}" class="btn-ghost btn-lg">{{ $secondaryText }}</a>
            @endif
        </div>
    </div>
</section>
