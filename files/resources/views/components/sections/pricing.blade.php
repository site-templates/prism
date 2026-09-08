@props([
    'recommendedLabel' => 'Most teams',
    'footnote' => 'Provider cost is passed through at list price on every plan. Annual billing takes two months off the platform fee.',
    'items',
    'lines',
])
<!--
    Three tiers in one grid. The recommended one wears an ink hairline and
    carries the section's one filled button; the others use the ghost. Each
    tier's lines come from the planLines collection, matched on the plan key,
    so every line is editable. Buttons align across the row.
-->
<section class="pb-20 md:pb-28">
    <div class="mx-auto max-w-page px-6">
        <div class="stagger grid gap-4 lg:grid-cols-3" data-reveal>
            @foreach ($items as $plan)
            @if ($plan->recommended == '1')
            <div class="tier frame flex flex-col justify-between p-8" data-recommended>
            @else
            <div class="tier frame flex flex-col justify-between p-8">
            @endif
                <div>
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="title-card text-ink">{{ $plan->name }}</h3>
                        @if ($plan->recommended == '1')
                        <span class="rounded-full bg-accent-wash px-2.5 py-0.5 text-xs font-medium text-accent">{{ $recommendedLabel }}</span>
                        @endif
                    </div>
                    <p class="mt-2 text-sm text-pretty text-muted">{{ $plan->blurb }}</p>
                    <p class="display-stat num mt-8 font-display text-ink">{{ $plan->price }}</p>
                    <p class="mt-2 text-sm text-pretty text-faint">{{ $plan->period }}</p>
                    <ul role="list" class="mt-8 flex flex-col gap-3 border-t border-line pt-8">
                        @foreach ($lines as $line)
                        @if ($line->plan == $plan->key)
                        <li class="flex items-start gap-3 text-sm text-muted">
                            <svg viewBox="0 0 16 16" class="mt-0.5 size-4 shrink-0 fill-accent" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/>
                            </svg>
                            {{ $line->text }}
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>
                <div class="mt-10">
                    @if ($plan->recommended == '1')
                    <a href="{{ $plan->buttonLink }}" class="btn-solid w-full">{{ $plan->buttonText }}</a>
                    @else
                    <a href="{{ $plan->buttonLink }}" class="btn-ghost w-full">{{ $plan->buttonText }}</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <p class="mt-8 max-w-[70ch] text-sm text-pretty text-faint" data-reveal>{{ $footnote }}</p>
    </div>
</section>
