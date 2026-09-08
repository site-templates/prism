@props([
    'items',
])
<!--
    The changelog: one entry per row — the date in a narrow left column, the
    title and paragraph on the right — with a hairline between entries.
    Entries come from the changelog collection, newest first in the file.
-->
<section class="pb-20 md:pb-28">
    <div class="mx-auto max-w-page px-6">
        <ol role="list" class="divide-y divide-line border-t border-line" data-reveal>
            @foreach ($items as $entry)
            <li class="grid gap-x-12 gap-y-2 py-8 lg:grid-cols-[12rem_minmax(0,1fr)]">
                <p class="num text-sm text-faint"><time datetime="{{ $entry->date }}">{{ $entry->dateFormatted }}</time></p>
                <div>
                    <h2 class="title-card text-ink">{{ $entry->title }}</h2>
                    <p class="mt-3 max-w-[64ch] text-base text-pretty text-muted">{{ $entry->body }}</p>
                </div>
            </li>
            @endforeach
        </ol>
    </div>
</section>
