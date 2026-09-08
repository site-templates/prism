<!-- The documentation index. Articles live in resources/data/collections/docs.json and open at /docs/{slug}. -->
<x-layouts.main title="Documentation" description="Quickstart, routing policies, fallbacks and retries, usage and limits." current="/docs">

    <x-sections.page-header heading="Documentation" body="Everything you need to route your first request and keep the bill where you put it." showBeams="0" />

    <x-sections.docs-index :items="$docs" />

</x-layouts.main>
