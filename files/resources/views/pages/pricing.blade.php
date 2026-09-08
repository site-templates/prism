<!-- Pricing: three tiers, the questions, and the closing call. -->
<x-layouts.main title="Pricing" description="A flat platform fee and provider prices passed through at list. Free for a million routed tokens a month." current="/pricing">

    <x-sections.page-header heading="A flat fee. Tokens at list price." body="Prism never marks up a token. You pay the platform fee for the router, the log and the fallbacks, and each provider's list price on one invoice." />

    <x-sections.pricing :items="$plans" :lines="$planLines" />

    <x-sections.faq :items="$faqs" />

    <x-sections.cta heading="Start on Free. Move up when the log earns it." body="Provider prices at list, on one invoice. Cancel from the dashboard." />

</x-layouts.main>
