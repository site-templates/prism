@props([
    'heading' => 'Built for the workloads that actually run.',
    'items',
])
<!--
    Three uses in one hairline-divided row — not cards. Each is a title, one
    sentence and one line of mono showing the setting that makes it work. On
    phones the dividers turn horizontal. Items come from the useCases collection.
-->
<section class="py-24 md:py-32">
    <div class="mx-auto max-w-page px-6">
        <h2 class="display-section max-w-[22ch] font-display text-balance text-ink" data-reveal>{{ $heading }}</h2>
        <dl class="stagger mt-12 grid gap-y-8 md:grid-cols-3 md:gap-y-0" data-reveal>
            @foreach ($items as $item)
            <div class="flex flex-col gap-3 border-t border-line pt-6 md:border-t-0 md:border-l md:pt-0 md:pr-8 md:pl-8 md:first:pl-0 md:first:border-l-0 md:last:pr-0">
                <dt class="title-card text-ink">{{ $item->title }}</dt>
                <dd class="text-base text-pretty text-muted">{{ $item->body }}</dd>
                <dd class="data mt-1 text-accent">{{ $item->example }}</dd>
            </div>
            @endforeach
        </dl>
    </div>
</section>
