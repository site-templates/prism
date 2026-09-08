<!-- The not-found page. -->
<x-layouts.main title="Page not found" description="That page does not exist.">

    <section class="grain relative overflow-hidden py-32 md:py-44">
        <div class="beams opacity-60" aria-hidden="true"></div>
        <div class="relative mx-auto flex max-w-page flex-col items-center px-6 text-center">
            <p class="data text-faint">404 · no route</p>
            <h1 class="display-page mt-4 max-w-[16ch] font-display text-balance text-ink">No model answers at this address.</h1>
            <p class="lede mt-5 max-w-[44ch] text-pretty text-muted">The page moved, was renamed, or never shipped. The homepage and the docs are one click away.</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="/" class="btn-solid btn-lg">Back to the homepage</a>
                <a href="/docs" class="btn-ghost btn-lg">Read the docs</a>
            </div>
        </div>
    </section>

</x-layouts.main>
