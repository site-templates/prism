@props([
    'title' => 'Post',
    'description' => '',
    'category' => 'Routing notes',
    'dateFormatted' => '',
    'readTime' => '',
    'author' => '',
    'authorRole' => '',
    'avatar' => '',
    'backText' => 'All posts',
    'backLink' => '/blog',
    'more',
])
{{--
    The reading shell for a blog post: the main layout, then a narrow column —
    a back link, the category and date, the serif title, the author row — the
    body in .prose, and three more posts under it. The dynamic blog page fills
    it from the matched entry of resources/data/collections/posts.json.
--}}
<x-layouts.main :title="$title" :description="$description" current="/blog">

    <article class="pt-16 pb-24 md:pt-24">
        <div class="mx-auto max-w-page px-6">
            <div class="mx-auto max-w-[68ch]">
                <a href="{{ $backLink }}" class="text-link inline-flex items-center gap-1.5 text-sm font-medium text-muted hover:text-ink">
                    <svg viewBox="0 0 16 16" class="size-4 shrink-0 fill-current" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9.78 4.22a.75.75 0 0 1 0 1.06L7.06 8l2.72 2.72a.75.75 0 1 1-1.06 1.06L5.47 8.53a.75.75 0 0 1 0-1.06l3.25-3.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/>
                    </svg>
                    {{ $backText }}
                </a>

                <p class="num mt-10 flex flex-wrap items-center gap-x-3 text-sm text-faint">
                    <span class="text-accent">{{ $category }}</span>
                    <span aria-hidden="true">·</span>
                    <span>{{ $dateFormatted }}</span>
                    <span aria-hidden="true">·</span>
                    <span>{{ $readTime }}</span>
                </p>

                <h1 class="display-page mt-4 font-display text-pretty text-ink">{{ $title }}</h1>
                <p class="lede mt-6 text-pretty text-muted">{{ $description }}</p>

                <div class="mt-8 flex items-center gap-3 border-t border-line pt-6">
                    <img src="{{ $avatar }}" alt="" width="40" height="40" class="size-10 shrink-0 rounded-full object-cover outline-1 -outline-offset-1 outline-line">
                    <div class="text-sm">
                        <p class="font-medium text-ink">{{ $author }}</p>
                        <p class="text-faint">{{ $authorRole }}</p>
                    </div>
                </div>
            </div>

            <div class="prose mx-auto mt-12 max-w-[68ch]">
                {{ $slot }}
            </div>
        </div>
    </article>

    <x-sections.posts heading="More from the blog" :items="$more" showIntro="0" />

</x-layouts.main>
