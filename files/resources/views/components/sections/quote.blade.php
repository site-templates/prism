@props([
    'quote' => 'We cut our model bill by a third in the first week and nobody on the team noticed a quality change. The log is what sold me: I can see why every request went where it went.',
    'name' => 'Priya Anand',
    'role' => 'Head of Platform, Loamworks',
    'avatar' => 'https://assets.ui.sh/avatars/3.webp?size=160',
])
<!--
    One testimonial, not a grid: a large serif quote set left with hanging
    punctuation, the avatar and attribution beneath. It sits on the well tint
    so the page breathes between the use cases and the blog.
-->
<section class="border-y border-line bg-well py-24 md:py-32">
    <div class="mx-auto max-w-page px-6">
        <figure class="max-w-[30ch] lg:max-w-[44ch]" data-reveal>
            <blockquote class="display-quote relative font-display text-pretty text-ink before:absolute before:inline before:-translate-x-full before:content-['\201C'] after:inline after:content-['\201D']">{{ $quote }}</blockquote>
            <figcaption class="mt-8 flex items-center gap-3">
                <img src="{{ $avatar }}" alt="" width="44" height="44" class="size-11 shrink-0 rounded-full object-cover outline-1 -outline-offset-1 outline-line">
                <div class="text-sm">
                    <p class="font-medium text-ink">{{ $name }}</p>
                    <p class="text-faint">{{ $role }}</p>
                </div>
            </figcaption>
        </figure>
    </div>
</section>
