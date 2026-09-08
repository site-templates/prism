<!-- The catalog: every model in the pool, filterable, and how routing scores them. -->
<x-layouts.main title="Models" description="Every model behind the Prism endpoint, with live list prices, latency and quality scores." current="/models">

    <x-sections.page-header heading="Every model in the pool, priced at list." body="Twelve families across eleven providers today, with new models routable the day they ship. Pin any id, or let a policy choose." />

    <x-sections.models-table :families="$families" :items="$models" />

    <x-sections.steps :items="$steps" />

    <x-sections.cta heading="Try a routed request against the whole catalog." body="Free for a million routed tokens a month. No card, no markup on tokens." />

</x-layouts.main>
