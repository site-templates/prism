@props([
    'heading' => 'How a request finds its model.',
    'body' => 'Three steps, a few milliseconds, and a line in the log for each.',
    'items',
])
<!--
    How routing works: a heading on the left, and on the right three numbered
    steps — Score, Rank, Route — as a hairline list. Items come from the steps
    collection.
-->
<section id="scoring" class="border-t border-line py-16 md:py-24">
    <div class="mx-auto max-w-page px-6">
        <div class="grid gap-x-12 gap-y-10 lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">
            <div data-reveal>
                <h2 class="display-section max-w-[16ch] font-display text-balance text-ink">{{ $heading }}</h2>
                <p class="lede mt-5 max-w-[40ch] text-pretty text-muted">{{ $body }}</p>
            </div>
            <ol role="list" class="stagger divide-y divide-line border-y border-line" data-reveal>
                @foreach ($items as $step)
                <li class="grid gap-x-8 gap-y-2 py-6 sm:grid-cols-[3rem_minmax(0,1fr)]">
                    <p class="num font-display text-2xl text-faint">{{ $loop->iteration }}</p>
                    <div>
                        <h3 class="title-card text-ink">{{ $step->title }}</h3>
                        <p class="mt-2 text-base text-pretty text-muted">{{ $step->body }}</p>
                    </div>
                </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>
