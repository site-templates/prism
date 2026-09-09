@props([
    'heading' => 'One key.',
    'headingTwo' => 'Every model.',
    'body' => 'Prism gives engineering teams one OpenAI-compatible endpoint in front of every model, and a log that says why each request went where it went.',
    'ctaText' => 'Get an API key',
    'ctaLink' => '/docs/quickstart',
    'showSecondary' => '1',
    'secondaryText' => 'See every model',
    'secondaryLink' => '/models',
    'pauseLabel' => 'Pause the tour',
])
<!--
    The opening. Iridescence fills the whole section — a canvas main.js paints
    with WebGL, a drifting field of the four beam colours seen through fluted
    glass, blooming violet under the pointer; without WebGL the same picture
    is drawn in CSS. Over it, left-aligned: the two-line serif headline, one
    paragraph and the pill button. Under that the tab strip, and under that
    Prism's signature — the router's own screen, drawn in markup, whose
    breadcrumb, table and assistant panel change with the pressed tab. The
    strip advances on its own until you press a tab or the pause button.
-->
<section id="router" class="relative overflow-hidden pt-20 pb-16 md:pt-28 md:pb-24">
    <div class="hero-sky" aria-hidden="true">
        <div class="beams"><canvas data-iridescence data-flutes="8"></canvas></div>
    </div>

    <div class="relative z-10 mx-auto max-w-page px-6">

        <div class="arrive flex flex-col items-start">
            <h1 class="display-hero font-display text-balance text-ink"><span class="block">{{ $heading }}</span><span class="block">{{ $headingTwo }}</span></h1>
            <p class="lede mt-7 max-w-[52ch] text-pretty text-muted">{{ $body }}</p>
            <div class="mt-9 flex flex-wrap items-center gap-x-7 gap-y-4">
                <a href="{{ $ctaLink }}" class="btn-solid btn-lg rounded-full">
                    {{ $ctaText }}
                    <svg viewBox="0 0 16 16" class="arrow size-4 shrink-0 fill-current" aria-hidden="true">
                        <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 0 1-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </a>
                @if ($showSecondary)
                <a href="{{ $secondaryLink }}" class="text-link inline-flex items-center gap-1.5 text-base font-medium text-ink">
                    {{ $secondaryText }}
                    <svg viewBox="0 0 16 16" class="arrow size-4 shrink-0 fill-current" aria-hidden="true">
                        <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 0 1-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </a>
                @endif
            </div>
        </div>

        <!-- The tab strip: one task per tab, and the button that stops the tour -->
        <div class="arrive-late mt-16 md:mt-24" data-tabs>
            <div class="-mx-6 overflow-x-auto px-6 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                <div class="flex min-w-max items-center gap-7 border-b border-line" role="tablist" aria-label="What the router does">
                    @foreach ($workbench as $tab)
                    @if ($loop->first)
                    <button type="button" class="tab" role="tab" aria-selected="true" data-tab-key="{{ $tab->key }}">{!! $tab->icon !!}{{ $tab->label }}</button>
                    @else
                    <button type="button" class="tab" role="tab" aria-selected="false" data-tab-key="{{ $tab->key }}">{!! $tab->icon !!}{{ $tab->label }}</button>
                    @endif
                    @endforeach
                    <button type="button" class="tab ml-auto pl-6" aria-label="{{ $pauseLabel }}" title="{{ $pauseLabel }}" data-tab-pause>
                        <svg viewBox="0 0 24 24" class="tab-pause size-4 shrink-0 fill-current" aria-hidden="true">
                            <path d="M8 5h3v14H8zM13 5h3v14h-3z"/>
                        </svg>
                        <svg viewBox="0 0 24 24" class="tab-play size-4 shrink-0 fill-current" aria-hidden="true">
                            <path d="M8 5.5v13l11-6.5z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="arrive-last mt-8">
                <x-mocks.console />
            </div>
        </div>

    </div>
</section>
