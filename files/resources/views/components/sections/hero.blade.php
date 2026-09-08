@props([
    'heading' => 'One key. Every model.',
    'body' => 'Prism sits in front of every model you pay for and routes each request to the right one — cheapest, fastest, best, or the one that fits the whole document.',
    'worksWith' => 'Works with',
    'placeholder' => 'Describe what you are building…',
    'sendLabel' => 'Route this prompt',
    'reason' => 'Routed to Gemini 3.1 Flash — 6× cheaper than a frontier model at equal quality on summaries.',
    'consoleTitle' => 'Routing console',
    'consoleNote' => 'Ranked for this prompt · live prices',
    'showSecondary' => '1',
    'secondaryText' => 'How routing works',
    'secondaryLink' => '/models#scoring',
    'routes',
    'models',
    'providers',
])
<!--
    The opening. A centered serif headline over the grained canvas with four
    pastel beams streaming behind it, one paragraph, and the provider dots.
    Then Prism's signature: the composer — a white 20px card with the prompt
    field, the send button and four chips — and, joined to it by a hairline,
    the routing console: five models ranked for the pressed chip. Pressing a
    chip fills the prompt, re-orders the rows (main.js) and hands the top row
    the teal Routed pill; the reason line under the table says why. The first
    chip ships pressed and the rows ship in its order, so it reads complete
    before any script runs. Chips come from the routes collection, the rows
    from console, the dots from providers.
-->
<section id="router" class="grain relative overflow-hidden pt-16 pb-20 md:pt-24 md:pb-28">
    <div class="beams" aria-hidden="true"></div>
    <div class="beams-fade" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-page px-6">
        <div class="arrive flex flex-col items-center text-center">
            <h1 class="display-hero max-w-[16ch] font-display text-balance text-ink">{{ $heading }}</h1>
            <p class="lede mt-6 max-w-[54ch] text-pretty text-muted">{{ $body }}</p>
            <p class="mt-5 flex flex-wrap items-center justify-center gap-x-2 gap-y-2 text-sm text-faint">
                <span>{{ $worksWith }}</span>
                <span class="flex -space-x-1.5" aria-label="Providers">
                    @foreach ($providers as $provider)
                    <span title="{{ $provider->name }}" class="flex size-6 items-center justify-center rounded-full bg-surface text-[0.625rem] font-semibold text-ink ring-1 ring-line">{{ $provider->initial }}</span>
                    @endforeach
                </span>
            </p>
        </div>

        <!-- The composer -->
        <div class="arrive-late mx-auto mt-12 max-w-3xl">
            <form class="composer relative" action="/docs/quickstart" method="get" data-composer>
                <label for="prompt" class="sr-only">Your prompt</label>
                <textarea id="prompt" name="prompt" rows="3" placeholder="{{ $placeholder }}" class="block w-full bg-transparent px-6 pt-6 pb-4 text-lg text-ink" data-composer-input></textarea>
                <div class="flex flex-wrap items-center gap-2 px-4 pb-4">
                    @foreach ($routes as $route)
                    @if ($loop->first)
                    <button type="button" class="chip" aria-pressed="true" data-route="{{ $route->key }}" data-prompt="{{ $route->prompt }}" data-reason="{{ $route->reason }}">{{ $route->label }}</button>
                    @else
                    <button type="button" class="chip" aria-pressed="false" data-route="{{ $route->key }}" data-prompt="{{ $route->prompt }}" data-reason="{{ $route->reason }}">{{ $route->label }}</button>
                    @endif
                    @endforeach
                    <button type="submit" aria-label="{{ $sendLabel }}" title="{{ $sendLabel }}" class="btn-solid ml-auto size-10 shrink-0 rounded-full p-0">
                        <svg viewBox="0 0 16 16" class="size-4 fill-current" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 14a.75.75 0 0 1-.75-.75V4.56L4.03 7.78a.75.75 0 0 1-1.06-1.06l4.5-4.5a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06L8.75 4.56v8.69A.75.75 0 0 1 8 14Z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- The hairline that joins the composer to the console, with the live dot -->
            <div class="flex justify-center" aria-hidden="true">
                <span class="block h-8 w-px bg-line-strong"></span>
            </div>

            <!-- The routing console -->
            <div class="arrive-last frame overflow-hidden" data-console>
                <div class="flex items-center gap-3 border-b border-line px-5 py-3">
                    <span class="live-dot shrink-0" aria-hidden="true"></span>
                    <p class="text-sm font-medium text-ink">{{ $consoleTitle }}</p>
                    <p class="ml-auto text-xs text-faint">{{ $consoleNote }}</p>
                </div>
                <ol role="list" class="divide-y divide-line">
                    @foreach ($models as $model)
                    @if ($loop->first)
                    <li class="console-row grid grid-cols-[minmax(0,1fr)_auto] items-center gap-x-4 gap-y-1 px-5 py-3.5 sm:grid-cols-[minmax(0,5fr)_minmax(0,2fr)_minmax(0,2fr)_minmax(0,2fr)_minmax(0,3fr)]" data-routed data-rank-cheap="{{ $model->rankCheap }}" data-rank-fast="{{ $model->rankFast }}" data-rank-code="{{ $model->rankCode }}" data-rank-long="{{ $model->rankLong }}">
                    @else
                    <li class="console-row grid grid-cols-[minmax(0,1fr)_auto] items-center gap-x-4 gap-y-1 px-5 py-3.5 sm:grid-cols-[minmax(0,5fr)_minmax(0,2fr)_minmax(0,2fr)_minmax(0,2fr)_minmax(0,3fr)]" data-rank-cheap="{{ $model->rankCheap }}" data-rank-fast="{{ $model->rankFast }}" data-rank-code="{{ $model->rankCode }}" data-rank-long="{{ $model->rankLong }}">
                    @endif
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="routed-pill items-center rounded-full bg-accent px-2 py-0.5 text-xs font-medium text-accent-ink">Routed</span>
                            <span class="min-w-0">
                                <span class="block truncate text-sm font-medium text-ink">{{ $model->name }}</span>
                                <span class="data block truncate text-faint">{{ $model->id }}</span>
                            </span>
                        </div>
                        <p class="text-sm text-muted max-sm:hidden">{{ $model->provider }}</p>
                        <p class="num text-sm text-muted max-sm:hidden">{{ $model->latency }}</p>
                        <p class="num text-sm text-muted max-sm:hidden">{{ $model->price }}<span class="text-faint">/M</span></p>
                        <div class="flex items-center justify-end gap-3">
                            <span class="score [--score:{{ $model->score }}%]" aria-hidden="true"></span>
                            <span class="num w-6 text-right text-sm text-ink">{{ $model->score }}</span>
                        </div>
                    </li>
                    @endforeach
                </ol>
                <p class="console-reason border-t border-line bg-well px-5 py-3 text-sm text-pretty text-muted" data-console-reason>{{ $reason }}</p>
            </div>
        </div>

        @if ($showSecondary)
        <p class="mt-12 text-center">
            <a href="{{ $secondaryLink }}" class="text-link inline-flex items-center gap-2 text-sm font-medium text-ink">
                <svg viewBox="0 0 16 16" class="size-4 shrink-0 fill-current" aria-hidden="true">
                    <path fill-rule="evenodd" d="M2 8a6 6 0 1 1 12 0A6 6 0 0 1 2 8Zm4.5-2.598v5.196a.5.5 0 0 0 .757.429l4.33-2.598a.5.5 0 0 0 0-.858l-4.33-2.598a.5.5 0 0 0-.757.429Z" clip-rule="evenodd"/>
                </svg>
                {{ $secondaryText }}
            </a>
        </p>
        @endif
    </div>
</section>
