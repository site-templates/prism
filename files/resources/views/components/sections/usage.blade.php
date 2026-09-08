@props([
    'heading' => 'See every token you spend.',
    'body' => 'Spend, tokens and latency by model, key and policy — live, and exportable as a row per request. The bill stops being a surprise.',
    'pointOne' => 'Per-key budgets with alerts at 50, 80 and 100 percent, and a soft-stop that downgrades instead of failing.',
    'pointTwo' => 'Quality scores from a judge you pick, on your own traffic, so the router learns what good looks like for you.',
    'linkText' => 'Usage and limits',
    'link' => '/docs/usage-and-limits',
    'mockTitle' => 'Spend by model',
    'mockPeriod' => 'September, to date',
    'mockTotal' => '$4,322',
    'mockDelta' => '−31% vs. August at the same point',
    'bars',
])
<!--
    The second split, mirrored: the drawn usage chart on the left and the
    argument on the right. Bars come from the usageBars collection. On phones
    the text comes first and the chart follows.
-->
<section id="usage" class="py-16 md:py-24">
    <div class="mx-auto max-w-page px-6">
        <div class="grid gap-x-12 gap-y-10 lg:grid-cols-2 lg:items-center">
            <div class="max-lg:order-2" data-reveal>
                <x-mocks.usage :title="$mockTitle" :period="$mockPeriod" :total="$mockTotal" :delta="$mockDelta" :bars="$bars" />
            </div>
            <div class="max-lg:order-1" data-reveal>
                <h2 class="display-section max-w-[20ch] font-display text-balance text-ink">{{ $heading }}</h2>
                <p class="lede mt-5 max-w-[48ch] text-pretty text-muted">{{ $body }}</p>
                <ul role="list" class="mt-8 flex flex-col gap-4 border-t border-line pt-8">
                    <li class="flex items-start gap-3 text-base text-pretty text-muted">
                        <svg viewBox="0 0 16 16" class="mt-1 size-4 shrink-0 fill-accent" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/>
                        </svg>
                        {{ $pointOne }}
                    </li>
                    <li class="flex items-start gap-3 text-base text-pretty text-muted">
                        <svg viewBox="0 0 16 16" class="mt-1 size-4 shrink-0 fill-accent" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/>
                        </svg>
                        {{ $pointTwo }}
                    </li>
                </ul>
                <a href="{{ $link }}" class="text-link mt-8 inline-flex items-center gap-1.5 text-base font-medium text-ink">
                    {{ $linkText }}
                    <svg viewBox="0 0 16 16" class="arrow size-4 shrink-0 fill-current" aria-hidden="true">
                        <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 0 1-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>
