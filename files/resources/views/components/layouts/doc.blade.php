@props([
    'title' => 'Documentation',
    'description' => '',
    'section' => '',
    'indexText' => 'Documentation',
    'indexLink' => '/docs',
    'nav',
])
{{--
    The reading shell for a docs article: the main layout, then a two-column
    grid — a sticky sidebar listing every article (from the whole docs
    collection, passed in as nav) and the article itself. On phones the
    sidebar becomes a row of chips above the article.
--}}
<x-layouts.main :title="$title" :description="$description" current="/docs">

    <div class="pt-12 pb-24 md:pt-16">
        <div class="mx-auto max-w-page px-6">
            <div class="grid gap-x-12 gap-y-10 lg:grid-cols-[14rem_minmax(0,1fr)]">

                <aside class="min-w-0 lg:sticky lg:top-24 lg:self-start">
                    <a href="{{ $indexLink }}" class="text-link text-sm font-medium text-ink">{{ $indexText }}</a>
                    <nav class="mt-4" aria-label="Documentation">
                        <ul role="list" class="flex gap-2 overflow-x-auto pb-2 lg:flex-col lg:gap-0 lg:border-l lg:border-line lg:pb-0">
                            @foreach ($nav as $item)
                            <li class="shrink-0">
                                @if ($item->title == $title)
                                <a href="{{ $item->link }}" aria-current="page" class="chip lg:-ml-px lg:block lg:min-h-0 lg:rounded-none lg:border-l lg:border-ink lg:bg-transparent lg:py-1.5 lg:pl-4 lg:pr-0 lg:text-sm lg:font-medium lg:text-ink lg:shadow-none" aria-pressed="true">{{ $item->title }}</a>
                                @else
                                <a href="{{ $item->link }}" class="chip lg:-ml-px lg:block lg:min-h-0 lg:rounded-none lg:border-l lg:border-transparent lg:bg-transparent lg:py-1.5 lg:pl-4 lg:pr-0 lg:text-sm lg:font-normal lg:text-muted lg:shadow-none lg:hover:border-line-strong lg:hover:bg-transparent lg:hover:text-ink">{{ $item->title }}</a>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </nav>
                </aside>

                <article class="min-w-0">
                    <p class="text-sm font-medium text-accent">{{ $section }}</p>
                    <h1 class="display-page mt-3 max-w-[20ch] font-display text-balance text-ink">{{ $title }}</h1>
                    <p class="lede mt-5 max-w-[56ch] text-pretty text-muted">{{ $description }}</p>

                    <div class="prose mt-10 max-w-[68ch]">
                        {{ $slot }}
                    </div>
                </article>

            </div>
        </div>
    </div>

</x-layouts.main>
