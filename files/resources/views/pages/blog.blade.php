<!-- The blog index. Posts live in resources/data/collections/posts.json and open at /blog/{slug}. -->
<x-layouts.main title="Blog" description="Benchmarks, routing notes and what we learned running traffic for other teams." current="/blog">

    <x-sections.page-header heading="Notes from the router." body="Benchmarks we ran, decisions we made, and the occasional incident written up honestly." showBeams="0" />

    <x-sections.posts heading="All posts" showIntro="0" linkText="Changelog" link="/changelog" :items="$posts" />

    <x-sections.cta />

</x-layouts.main>
