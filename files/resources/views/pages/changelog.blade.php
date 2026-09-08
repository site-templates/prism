<!-- The changelog. Entries live in resources/data/collections/changelog.json, newest first. -->
<x-layouts.main title="Changelog" description="New models, features and fixes, shipped weekly." current="/changelog">

    <x-sections.page-header heading="Changelog" body="New models the day they ship, and everything else we changed, newest first." showBeams="0" />

    <x-sections.changelog-list :items="$changelog" />

</x-layouts.main>
