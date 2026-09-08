{{-- Dynamic page: one URL per entry of resources/data/collections/docs.json, matched on `slug` — $docs is the matched entry. Add an entry there to publish an article; its `content` HTML is the body, and $entries is the whole collection for the sidebar. --}}
<x-layouts.doc
    :title="$docs->title"
    :description="$docs->description"
    :section="$docs->section"
    :nav="$entries">

    {!! $docs->content !!}

</x-layouts.doc>
