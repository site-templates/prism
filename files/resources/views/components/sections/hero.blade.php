@props([
    'heading' => 'One key.',
    'headingTwo' => 'Every model.',
    'body' => 'One OpenAI-compatible key in front of every model. Each request goes to the right one — cheapest, fastest, best, or the one that fits the whole document.',
    'ctaText' => 'Get an API key',
    'ctaLink' => '/docs/quickstart',
    'showSecondary' => '1',
    'secondaryText' => 'See every model',
    'secondaryLink' => '/models',
    'worksWith' => 'Works with',
    'placeholder' => 'Describe what you are building…',
    'sendLabel' => 'Route this prompt',
    'routedLabel' => 'Routed to',
    'reason' => '6× cheaper than a frontier model at equal quality on summaries.',
    'routes',
    'models',
    'providers',
])
<!--
    The opening, the way Stripe sets it: a vivid mesh gradient (a WebGL canvas
    main.js paints from the beam tokens; the CSS beams stand in without WebGL)
    filling the top of the page and ending in a diagonal edge, the two-line
    serif headline, one paragraph, the ink button and a text link, and the
    provider marks on the left; on the right, floating over the edge, Prism's
    signature — the composer: a soft shell holding the white prompt card with
    the round send button, the four chips in the shell's band, and under them
    the route strip naming the model this prompt would go to. Pressing a chip
    fills the prompt and swaps the strip (main.js). The first chip ships
    pressed with its result shown, so it reads complete before any script.
    Chips come from the routes collection, the results from console (the
    rank-1 model per chip), the marks from providers.
-->
<section id="router" class="relative overflow-hidden pt-16 pb-20 md:pt-24 md:pb-28">
    <div class="hero-sky grain" aria-hidden="true">
        <div class="beams"><canvas data-mesh></canvas></div>
    </div>

    <div class="relative z-10 mx-auto max-w-page px-6">
        <div class="grid gap-x-12 gap-y-14 lg:grid-cols-[minmax(0,11fr)_minmax(0,9fr)] lg:items-center">

            <div class="arrive">
                <h1 class="display-hero font-display text-balance text-ink"><span class="block">{{ $heading }}</span><span class="block">{{ $headingTwo }}</span></h1>
                <p class="lede mt-8 max-w-[44ch] text-pretty text-muted">{{ $body }}</p>
                <div class="mt-10 flex flex-wrap items-center gap-x-7 gap-y-4">
                    <a href="{{ $ctaLink }}" class="btn-solid btn-lg">
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
                <p class="mt-10 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-muted">
                    <span>{{ $worksWith }}</span>
                    <span class="flex -space-x-2" aria-label="Providers">
                        @foreach ($providers as $provider)
                        <span title="{{ $provider->name }}" style="background-color: {{ $provider->color }}" class="flex size-8 items-center justify-center rounded-full text-[0.6875rem] font-semibold text-surface ring-2 ring-canvas">{{ $provider->initial }}</span>
                        @endforeach
                    </span>
                </p>
            </div>

            <!-- The composer: shell, white prompt card, chips, the route strip -->
            <form class="composer arrive-late lg:justify-self-end lg:w-full lg:max-w-[34rem]" action="/docs/quickstart" method="get" data-composer>
                <div class="composer-card relative">
                    <label for="prompt" class="sr-only">Your prompt</label>
                    <textarea id="prompt" name="prompt" rows="4" placeholder="{{ $placeholder }}" class="block w-full bg-transparent px-6 pt-6 pb-18 text-xl text-ink" data-composer-input></textarea>
                    <button type="submit" aria-label="{{ $sendLabel }}" title="{{ $sendLabel }}" class="btn-solid absolute right-4 bottom-4 size-11 rounded-full p-0">
                        <svg viewBox="0 0 16 16" class="size-4 fill-current" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 14a.75.75 0 0 1-.75-.75V4.56L4.03 7.78a.75.75 0 0 1-1.06-1.06l4.5-4.5a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06L8.75 4.56v8.69A.75.75 0 0 1 8 14Z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
                <div class="flex flex-wrap items-center gap-2 px-2 pt-2.5">
                    @foreach ($routes as $route)
                    @if ($loop->first)
                    <button type="button" class="chip" aria-pressed="true" data-route="{{ $route->key }}" data-prompt="{{ $route->prompt }}" data-reason="{{ $route->reason }}">{{ $route->label }}</button>
                    @else
                    <button type="button" class="chip" aria-pressed="false" data-route="{{ $route->key }}" data-prompt="{{ $route->prompt }}" data-reason="{{ $route->reason }}">{{ $route->label }}</button>
                    @endif
                    @endforeach
                </div>

                <!-- The route strip: one line per chip, the pressed chip's shown. Each names the rank-1 model for its key. -->
                <div class="route-strip mx-2 mt-3 border-t border-line px-1 pt-3 pb-1">
                    @foreach ($models as $model)
                    @if ($model->rankCheap == '1')
                    <p class="flex flex-wrap items-baseline gap-x-2 text-sm text-muted" data-route-result="cheap">
                        <span class="live-dot self-center" aria-hidden="true"></span>
                        <span>{{ $routedLabel }}</span>
                        <span class="font-medium text-ink">{{ $model->name }}</span>
                        <span class="num text-faint">{{ $model->latency }} · {{ $model->price }}/M</span>
                    </p>
                    @endif
                    @if ($model->rankFast == '1')
                    <p class="flex flex-wrap items-baseline gap-x-2 text-sm text-muted" data-route-result="fast" hidden>
                        <span class="live-dot self-center" aria-hidden="true"></span>
                        <span>{{ $routedLabel }}</span>
                        <span class="font-medium text-ink">{{ $model->name }}</span>
                        <span class="num text-faint">{{ $model->latency }} · {{ $model->price }}/M</span>
                    </p>
                    @endif
                    @if ($model->rankCode == '1')
                    <p class="flex flex-wrap items-baseline gap-x-2 text-sm text-muted" data-route-result="code" hidden>
                        <span class="live-dot self-center" aria-hidden="true"></span>
                        <span>{{ $routedLabel }}</span>
                        <span class="font-medium text-ink">{{ $model->name }}</span>
                        <span class="num text-faint">{{ $model->latency }} · {{ $model->price }}/M</span>
                    </p>
                    @endif
                    @if ($model->rankLong == '1')
                    <p class="flex flex-wrap items-baseline gap-x-2 text-sm text-muted" data-route-result="long" hidden>
                        <span class="live-dot self-center" aria-hidden="true"></span>
                        <span>{{ $routedLabel }}</span>
                        <span class="font-medium text-ink">{{ $model->name }}</span>
                        <span class="num text-faint">{{ $model->latency }} · {{ $model->price }}/M</span>
                    </p>
                    @endif
                    @endforeach
                    <p class="console-reason mt-1.5 text-sm text-pretty text-faint" data-console-reason>{{ $reason }}</p>
                </div>
            </form>

        </div>
    </div>
</section>
