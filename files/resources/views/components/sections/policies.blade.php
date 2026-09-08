@props([
    'heading' => 'Route by policy, not by hand.',
    'body' => 'A policy is a short file of rules: what to prefer, when to fall back, where the budget stops. Commit it beside your code and every request obeys it.',
    'pointOne' => 'Cheapest above a quality floor, fastest within a margin, best under a price cap — or a pinned model.',
    'pointTwo' => 'Fallbacks fire inside the same call, even mid-stream, and the log records both legs.',
    'linkText' => 'Read the policy reference',
    'link' => '/docs/routing-policies',
    'mockTitle' => 'production.policy.yml',
    'mockFooter' => 'Pushed 4 minutes ago · 2,318 requests routed since',
    'rules',
])
<!--
    The first split: the argument on the left — a serif heading, one
    paragraph, two points and a text link — and the drawn policy file on the
    right. Rules come from the policyRules collection. On phones the file
    follows the text.
-->
<section id="policies" class="py-20 md:py-28">
    <div class="mx-auto max-w-page px-6">
        <div class="grid gap-x-12 gap-y-10 lg:grid-cols-2 lg:items-center">
            <div data-reveal>
                <h2 class="display-section max-w-[20ch] font-display text-balance text-ink">{{ $heading }}</h2>
                <p class="lede mt-5 max-w-[48ch] text-pretty text-muted">{{ $body }}</p>
                <ul role="list" class="mt-8 flex flex-col gap-4 border-t border-line pt-8">
                    <li class="flex items-start gap-3 text-base text-pretty text-muted">
                        <svg viewBox="0 0 16 16" class="mt-1 size-4 shrink-0 fill-accent" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/>
                        </svg>
                        {{ $pointOne }}
                    </li>
                    <li class="flex items-start gap-3 text-base text-pretty text-muted">
                        <svg viewBox="0 0 16 16" class="mt-1 size-4 shrink-0 fill-accent" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/>
                        </svg>
                        {{ $pointTwo }}
                    </li>
                </ul>
                <a href="{{ $link }}" class="text-link mt-8 inline-flex items-center gap-1.5 text-base font-medium text-ink">
                    {{ $linkText }}
                    <svg viewBox="0 0 16 16" class="arrow size-4 shrink-0 fill-current" aria-hidden="true">
                        <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 0 1-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
            <div data-reveal>
                <x-mocks.policy :title="$mockTitle" :footer="$mockFooter" :rules="$rules" />
            </div>
        </div>
    </div>
</section>
