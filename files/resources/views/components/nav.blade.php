@props([
    'brand' => 'Prism',
    'links' => [],
    'current' => '/',
    'showAnnounce' => '1',
    'announceText' => 'Prism now speaks MCP — route every model from inside Claude.',
    'announceLinkText' => 'Read more',
    'announceLink' => '/changelog',
    'signInText' => 'Log in',
    'signInLink' => '#',
    'ctaText' => 'Get API key',
    'ctaLink' => '/docs/quickstart',
    'promoLabel' => 'See the router at work',
    'promoBody' => 'A two-minute walkthrough of policies, fallbacks and the log.',
    'promoButtonText' => 'Watch the demo',
    'promoLink' => '/#router',
])
<!--
    The site-wide bar, the way tables.so sets it: the mark and wordmark on the
    left, the page links beside them, and on the right a ghost log-in and the
    single ink button. A link with children renders as the mega-menu — two
    columns of titled links with a one-line description each, and a dark promo
    card on the right. Hover opens it with an intent delay, click toggles it on
    touch, Escape dismisses (main.js). Links come from nav_links in
    resources/data/site.json, so one edit changes every page. On phones the
    hamburger opens a full sheet. A dismissible announcement line sits above it.
-->
<header id="header" class="sticky top-0 z-50">
    <div class="header-chrome relative z-10">
    @if ($showAnnounce)
    <div id="announce" class="relative border-b border-line bg-well">
        <div class="mx-auto flex h-10 max-w-page items-center justify-center gap-2 px-6 pr-14 text-sm text-muted">
            <span class="live-dot shrink-0" aria-hidden="true"></span>
            <p class="truncate">{{ $announceText }} <a href="{{ $announceLink }}" class="text-link font-medium text-ink">{{ $announceLinkText }} →</a></p>
        </div>
        <button type="button" data-announce-dismiss aria-label="Dismiss" class="absolute top-1/2 right-4 flex size-8 -translate-y-1/2 items-center justify-center rounded-md text-faint hover:bg-raised hover:text-ink">
            <svg viewBox="0 0 16 16" class="size-4 fill-current" aria-hidden="true">
                <path d="M5.28 4.22a.75.75 0 0 0-1.06 1.06L6.94 8l-2.72 2.72a.75.75 0 1 0 1.06 1.06L8 9.06l2.72 2.72a.75.75 0 1 0 1.06-1.06L9.06 8l2.72-2.72a.75.75 0 0 0-1.06-1.06L8 6.94 5.28 4.22Z"/>
            </svg>
        </button>
    </div>
    @endif

    <div class="relative mx-auto flex h-18 max-w-page items-center gap-8 px-6">

        <!-- href="/" always points to your site's root, in preview and when published -->
        <a href="/" aria-label="Homepage" class="brand flex shrink-0 items-center gap-2 text-ink">
            <svg viewBox="0 0 24 24" class="size-6 shrink-0" aria-hidden="true">
                <path fill="currentColor" d="M12 2.25 22.5 21H1.5Z"/>
                <path class="stroke-canvas" fill="none" stroke-width="1.5" stroke-linecap="round" d="M9 14h7.5M9 17h5"/>
            </svg>
            <span class="text-[1.0625rem] font-semibold tracking-[-0.02em]">{{ $brand }}</span>
        </a>

        <nav class="flex h-full items-center max-lg:hidden" aria-label="Main">
            <ul role="list" class="flex items-center gap-1">
                @foreach ($links as $link)
                @if ($link->children ?? false)
                <li class="relative" data-dropdown>
                    <button type="button" data-dropdown-trigger aria-expanded="false" class="nav-link flex cursor-pointer items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-muted hover:bg-well hover:text-ink">
                        {{ $link->text }}
                        <svg viewBox="0 0 16 16" class="dropdown-caret size-3.5 shrink-0 fill-current opacity-60" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <!-- The mega-menu panel: link grid on the left, the dark promo card on the right. -->
                    <div data-dropdown-panel class="absolute top-full left-0 w-[46rem] pt-2">
                        <div class="grid grid-cols-[minmax(0,1fr)_15rem] gap-2 rounded-[1.25rem] bg-surface p-2 shadow-float ring-1 ring-line">
                            <div class="grid grid-cols-2 gap-x-2 gap-y-0.5 p-2">
                                @foreach ($link->children as $child)
                                <a href="{{ $child->url }}" class="group flex items-start rounded-xl p-3 hover:bg-well">
                                    <span class="min-w-0">
                                        <span class="block text-sm font-medium text-ink">{{ $child->text }}</span>
                                        @if ($child->description ?? false)
                                        <span class="mt-0.5 block text-[0.8125rem]/5 text-pretty text-muted">{{ $child->description }}</span>
                                        @endif
                                    </span>
                                </a>
                                @endforeach
                            </div>

                            <a href="{{ $promoLink }}" class="group flex flex-col rounded-[0.875rem] bg-band p-4 text-band-ink">
                                <span class="block overflow-hidden rounded-lg bg-band-raised ring-1 ring-band-line">
                                    <x-mocks.promo />
                                </span>
                                <span class="mt-3 block text-sm font-medium">{{ $promoLabel }}</span>
                                <span class="mt-1 block text-[0.8125rem]/5 text-pretty text-band-muted">{{ $promoBody }}</span>
                                <span class="btn-band btn-sm mt-4 self-start">{{ $promoButtonText }}</span>
                            </a>
                        </div>
                    </div>
                </li>
                @else
                <li>
                    @if ($link->url == $current)
                    <a href="{{ $link->url }}" aria-current="page" class="nav-link block rounded-lg px-3 py-2 text-sm font-medium text-ink">{{ $link->text }}</a>
                    @else
                    <a href="{{ $link->url }}" class="nav-link block rounded-lg px-3 py-2 text-sm font-medium text-muted hover:bg-well hover:text-ink">{{ $link->text }}</a>
                    @endif
                </li>
                @endif
                @endforeach
            </ul>
        </nav>

        <div class="ml-auto flex items-center gap-3">
            <a href="{{ $signInLink }}" class="btn-ghost btn-sm max-lg:hidden">{{ $signInText }}</a>
            <a href="{{ $ctaLink }}" class="btn-solid btn-sm max-lg:hidden">{{ $ctaText }}</a>

            <button type="button" data-menu-toggle aria-expanded="false" aria-controls="mobile-menu" aria-label="Menu" class="relative flex size-11 items-center justify-center rounded-lg text-ink shadow-[inset_0_0_0_1px_var(--color-line-strong)] hover:bg-well lg:hidden">
                <svg viewBox="0 0 20 20" class="menu-icon-open size-5 fill-current" aria-hidden="true">
                    <path fill-rule="evenodd" d="M2 5.75A.75.75 0 0 1 2.75 5h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 5.75Zm0 8.5a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"/>
                </svg>
                <svg viewBox="0 0 20 20" class="menu-icon-close size-5 fill-current" aria-hidden="true">
                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                </svg>
            </button>
        </div>

    </div>
    </div>

    <!-- The phone sheet. Hidden until the hamburger sets data-menu-open on the header. -->
    <div id="mobile-menu" class="fixed inset-x-0 bottom-0 z-0 flex-col overflow-y-auto bg-canvas px-6 pt-4 pb-8 lg:hidden">
            <nav aria-label="Main, mobile">
                <ul role="list" class="flex flex-col divide-y divide-line">
                    @foreach ($links as $link)
                    @if ($link->children ?? false)
                    <li class="py-4">
                        <p class="text-sm font-medium text-faint">{{ $link->text }}</p>
                        <ul role="list" class="mt-2 grid gap-1 sm:grid-cols-2">
                            @foreach ($link->children as $child)
                            <li><a href="{{ $child->url }}" class="block rounded-lg py-2 text-lg font-medium text-ink hover:text-accent">{{ $child->text }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    @else
                    <li>
                        @if ($link->url == $current)
                        <a href="{{ $link->url }}" aria-current="page" class="block py-4 text-lg font-medium text-ink">{{ $link->text }}</a>
                        @else
                        <a href="{{ $link->url }}" class="block py-4 text-lg font-medium text-ink hover:text-accent">{{ $link->text }}</a>
                        @endif
                    </li>
                    @endif
                    @endforeach
                </ul>
            </nav>
            <div class="mt-auto flex flex-col gap-3 pt-6">
                <a href="{{ $ctaLink }}" class="btn-solid btn-lg w-full">{{ $ctaText }}</a>
                <a href="{{ $signInLink }}" class="btn-ghost btn-lg w-full">{{ $signInText }}</a>
            </div>
        </div>
</header>
