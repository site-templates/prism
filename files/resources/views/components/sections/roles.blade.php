@props([
    'heading' => 'Open roles',
    'body' => 'Small team, real traffic, no growth theatre. Write to us with something you have shipped.',
    'applyText' => 'Apply',
    'items',
])
<!--
    Open roles as hairline rows: title, place, and the apply link. Items come
    from the roles collection.
-->
<section id="roles" class="border-t border-line py-16 md:py-24">
    <div class="mx-auto max-w-page px-6">
        <div class="grid gap-x-12 gap-y-10 lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">
            <div data-reveal>
                <h2 class="display-section max-w-[14ch] font-display text-balance text-ink">{{ $heading }}</h2>
                <p class="lede mt-5 max-w-[40ch] text-pretty text-muted">{{ $body }}</p>
            </div>
            <ul role="list" class="divide-y divide-line border-y border-line" data-reveal>
                @foreach ($items as $role)
                <li>
                    <a href="{{ $role->url }}" class="group flex items-baseline justify-between gap-6 py-5">
                        <span class="min-w-0">
                            <span class="block text-lg font-medium text-ink group-hover:text-accent">{{ $role->title }}</span>
                            <span class="mt-1 block text-sm text-faint">{{ $role->place }}</span>
                        </span>
                        <span class="text-link inline-flex shrink-0 items-center gap-1.5 text-sm font-medium text-ink">
                            {{ $applyText }}
                            <svg viewBox="0 0 16 16" class="arrow size-4 shrink-0 fill-current" aria-hidden="true">
                                <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 0 1-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
