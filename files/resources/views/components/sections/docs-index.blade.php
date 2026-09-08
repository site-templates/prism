@props([
    'items',
])
<!--
    The docs index: every article as a hairline row — the section label, the
    title, the one-line description — the whole row a link. Items come from
    the docs collection, the same entries the article pages read.
-->
<section class="pb-20 md:pb-28">
    <div class="mx-auto max-w-page px-6">
        <ol role="list" class="divide-y divide-line border-y border-line" data-reveal>
            @foreach ($items as $doc)
            <li>
                <a href="{{ $doc->link }}" class="group grid gap-x-12 gap-y-1 py-6 lg:grid-cols-[12rem_minmax(0,1fr)_auto] lg:items-baseline">
                    <p class="text-sm text-faint">{{ $doc->section }}</p>
                    <span class="min-w-0">
                        <span class="block text-lg font-medium text-ink group-hover:text-accent">{{ $doc->title }}</span>
                        <span class="mt-1 block max-w-[60ch] text-base text-pretty text-muted">{{ $doc->description }}</span>
                    </span>
                    <svg viewBox="0 0 16 16" class="arrow size-4 shrink-0 fill-faint max-lg:hidden group-hover:fill-accent" aria-hidden="true">
                        <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 0 1-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </li>
            @endforeach
        </ol>
    </div>
</section>
