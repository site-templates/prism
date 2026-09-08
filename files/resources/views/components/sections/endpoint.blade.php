@props([
    'heading' => 'Every model behind one endpoint.',
    'body' => 'Prism is OpenAI-compatible. Change the base URL and the model id, keep everything else, and the whole catalog is one request away.',
    'ctaText' => 'Read the quickstart',
    'ctaLink' => '/docs/quickstart',
    'fileName' => 'route.py',
    'stats',
])
<!--
    The one dark band on the page. Full-bleed ink, the serif heading and one
    paragraph on the left with the canvas-coloured button, a code block on the
    right showing the two lines that change, and four figures in a hairline
    row underneath. Figures come from the stats collection.
-->
<section class="bg-band py-20 text-band-ink md:py-28">
    <div class="mx-auto max-w-page px-6">
        <div class="grid gap-x-12 gap-y-10 lg:grid-cols-2 lg:items-center">
            <div data-reveal>
                <h2 class="display-section max-w-[18ch] font-display text-balance">{{ $heading }}</h2>
                <p class="lede mt-5 max-w-[46ch] text-pretty text-band-muted">{{ $body }}</p>
                <a href="{{ $ctaLink }}" class="btn-band btn-lg mt-8">
                    {{ $ctaText }}
                    <svg viewBox="0 0 16 16" class="arrow size-4 shrink-0 fill-current" aria-hidden="true">
                        <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 0 1-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>

            <div class="min-w-0 overflow-hidden rounded-xl bg-band-raised ring-1 ring-band-line" data-reveal>
                <div class="flex items-center gap-3 border-b border-band-line px-5 py-3">
                    <span class="flex gap-1.5" aria-hidden="true">
                        <span class="block size-2.5 rounded-full bg-band-line"></span>
                        <span class="block size-2.5 rounded-full bg-band-line"></span>
                        <span class="block size-2.5 rounded-full bg-band-line"></span>
                    </span>
                    <p class="data text-band-muted">{{ $fileName }}</p>
                </div>
<pre class="code overflow-x-auto px-5 py-5 text-band-ink"><code><span class="tok-key">from</span> openai <span class="tok-key">import</span> OpenAI

client = <span class="tok-fn">OpenAI</span>(
    base_url=<span class="tok-str">"https://api.prism.example/v1"</span>,  <span class="tok-cm"># was api.openai.com</span>
    api_key=<span class="tok-str">"pk_live_…"</span>,
)

reply = client.chat.completions.<span class="tok-fn">create</span>(
    model=<span class="tok-str">"prism/auto"</span>,  <span class="tok-cm"># the policy picks the model</span>
    messages=[{<span class="tok-str">"role"</span>: <span class="tok-str">"user"</span>, <span class="tok-str">"content"</span>: prompt}],
)

<span class="tok-key">print</span>(reply.headers[<span class="tok-str">"x-prism-route"</span>])
<span class="tok-cm"># → google/gemini-3.1-flash · cheapest_above(80) · $0.0021</span></code></pre>
            </div>
        </div>

        <dl class="mt-16 grid grid-cols-2 gap-x-8 gap-y-8 border-t border-band-line pt-10 lg:grid-cols-4 lg:mt-20" data-reveal>
            @foreach ($stats as $stat)
            <div class="flex flex-col gap-2">
                <dd class="display-stat font-display">{{ $stat->value }}</dd>
                <dt class="text-sm text-pretty text-band-muted">{{ $stat->label }}</dt>
            </div>
            @endforeach
        </dl>
    </div>
</section>
