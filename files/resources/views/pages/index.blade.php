<!-- The home page. Each section's text is set in its own component's defaults; pass an attribute here only to override one. -->
<x-layouts.main title="Home" description="Prism routes every request to the right model — cheapest, fastest, best or longest context — behind one OpenAI-compatible key." current="/">

    <x-sections.hero />

    <x-sections.providers :items="$providers" />

    <x-sections.policies :rules="$policyRules" />

    <x-sections.usage :bars="$usageBars" />

    <x-sections.endpoint :stats="$stats" />

    <x-sections.use-cases :items="$useCases" />

    <x-sections.quote />

    <x-sections.posts :items="$posts" />

    <x-sections.cta />

</x-layouts.main>
