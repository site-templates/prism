@props([
    'heading' => 'Page',
    'body' => '',
    'showBeams' => '1',
])
<!--
    Opens every inner page: a left-aligned serif headline and one paragraph
    over the grained canvas, with the beams behind it unless turned off.
-->
<section class="grain relative overflow-hidden pt-16 pb-12 md:pt-24 md:pb-16">
    @if ($showBeams)
    <div class="beams opacity-60" aria-hidden="true"></div>
    <div class="beams-fade" aria-hidden="true"></div>
    @endif
    <div class="arrive relative mx-auto max-w-page px-6">
        <h1 class="display-page max-w-[18ch] font-display text-balance text-ink">{{ $heading }}</h1>
        @if ($body)
        <p class="lede mt-5 max-w-[54ch] text-pretty text-muted">{{ $body }}</p>
        @endif
    </div>
</section>
