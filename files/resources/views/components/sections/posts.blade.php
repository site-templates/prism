@props([
    'heading' => 'From the blog',
    'showIntro' => '1',
    'intro' => 'Benchmarks, routing notes and what we learned running traffic for other teams.',
    'linkText' => 'All posts',
    'link' => '/blog',
    'items',
])
<!--
    The blog as a list, not a card grid: a heading with the all-posts link on
    the right, then one hairline row per post — the date and read time, the
    title, the category. The whole row is the link. Items come from the posts
    collection; the post layout reuses this with showIntro off for "more".
-->
<section class="py-16 md:py-24">
    <div class="mx-auto max-w-page px-6">
        <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
            <div>
                <h2 class="display-section font-display text-balance text-ink">{{ $heading }}</h2>
                @if ($showIntro)
                <p class="lede mt-4 max-w-[48ch] text-pretty text-muted">{{ $intro }}</p>
                @endif
            </div>
            <a href="{{ $link }}" class="text-link inline-flex items-center gap-1.5 text-base font-medium text-ink">
                {{ $linkText }}
                <svg viewBox="0 0 16 16" class="arrow size-4 shrink-0 fill-current" aria-hidden="true">
                    <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 0 1-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                </svg>
            </a>
        </div>
        <ol role="list" class="mt-10 divide-y divide-line border-y border-line" data-reveal>
            @foreach ($items as $post)
            <li>
                <a href="{{ $post->link }}" class="group grid gap-x-8 gap-y-1 py-5 sm:grid-cols-[10rem_minmax(0,1fr)_8rem] sm:items-baseline">
                    <p class="num text-sm text-faint">{{ $post->dateFormatted }}</p>
                    <p class="text-lg font-medium text-pretty text-ink group-hover:text-accent">{{ $post->title }}</p>
                    <p class="text-sm text-faint sm:text-right">{{ $post->readTime }}</p>
                </a>
            </li>
            @endforeach
        </ol>
    </div>
</section>
