@props([
    'brand' => 'Prism',
    'blurb' => 'One OpenAI-compatible endpoint in front of every model you pay for, with a log that says why each request went where it went.',
    'statusText' => 'All providers operational',
    'statusLink' => '/changelog',
    'copyright' => '© 2026 Prism Labs, Inc.',
    'legalLeft' => 'Pass-through pricing',
    'legalRight' => 'Frankfurt · Virginia · Oregon',
    'columns',
])
<!--
    The close of every page: the mark and one paragraph with the live status
    line on the left, four link columns on the right, and a hairline legal
    row. Columns come from footer_columns in resources/data/site.json.
-->
<footer class="border-t border-line bg-canvas">
    <div class="mx-auto max-w-page px-6 py-16 md:py-20">
        <div class="grid gap-x-12 gap-y-12 lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">

            <div class="flex flex-col items-start gap-6">
                <a href="/" aria-label="Homepage" class="brand flex items-center gap-2 text-ink">
                    <svg viewBox="0 0 24 24" class="size-6 shrink-0" aria-hidden="true">
                        <path fill="currentColor" d="M12 2.25 22.5 21H1.5Z"/>
                        <path class="stroke-canvas" fill="none" stroke-width="1.5" stroke-linecap="round" d="M9 14h7.5M9 17h5"/>
                    </svg>
                    <span class="text-[1.0625rem] font-semibold tracking-[-0.02em]">{{ $brand }}</span>
                </a>
                <p class="max-w-[40ch] text-sm/6 text-pretty text-muted">{{ $blurb }}</p>
                <a href="{{ $statusLink }}" class="text-link inline-flex items-center gap-2.5 text-sm text-muted hover:text-ink">
                    <span class="live-dot shrink-0" aria-hidden="true"></span>
                    {{ $statusText }}
                </a>
            </div>

            <nav aria-label="Footer">
                <ul role="list" class="grid grid-cols-2 gap-x-8 gap-y-10 sm:grid-cols-4">
                    @foreach ($columns as $column)
                    <li>
                        <p class="text-sm font-medium text-ink">{{ $column->text }}</p>
                        @if ($column->children ?? false)
                        <ul role="list" class="mt-4 flex flex-col gap-2.5">
                            @foreach ($column->children as $child)
                            <li><a href="{{ $child->url }}" class="text-link text-sm text-muted hover:text-ink">{{ $child->text }}</a></li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </nav>

        </div>

        <div class="mt-16 flex flex-col gap-2 border-t border-line pt-6 text-sm text-faint sm:flex-row sm:items-center sm:justify-between">
            <p>{{ $copyright }}</p>
            <p>{{ $legalLeft }}</p>
            <p class="num">{{ $legalRight }}</p>
        </div>
    </div>
</footer>
