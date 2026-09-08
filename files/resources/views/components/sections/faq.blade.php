@props([
    'heading' => 'Questions, answered.',
    'items',
])
<!--
    The heading on the left, the questions on the right as native disclosure
    rows that animate open, one hairline between each. Items come from the
    faqs collection.
-->
<section class="border-t border-line py-16 md:py-24">
    <div class="mx-auto max-w-page px-6">
        <div class="grid gap-x-12 gap-y-10 lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">
            <h2 class="display-section max-w-[14ch] font-display text-balance text-ink" data-reveal>{{ $heading }}</h2>
            <div class="divide-y divide-line border-y border-line" data-reveal>
                @foreach ($items as $item)
                <details class="group py-2" data-faq>
                    <summary class="flex cursor-pointer items-start justify-between gap-6 py-4 text-left text-lg font-medium text-ink [&::-webkit-details-marker]:hidden">
                        {{ $item->question }}
                        <svg viewBox="0 0 16 16" class="faq-sign mt-1 size-4 shrink-0 fill-faint" aria-hidden="true">
                            <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z"/>
                        </svg>
                    </summary>
                    <div class="faq-answer">
                        <p class="max-w-[62ch] pb-5 text-base text-pretty text-muted">{{ $item->answer }}</p>
                    </div>
                </details>
                @endforeach
            </div>
        </div>
    </div>
</section>
