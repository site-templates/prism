{{-- Dynamic page: one URL per entry of resources/data/collections/posts.json, matched on `slug` — $posts is the matched entry. Add an entry there to publish a post; its `content` HTML is the body, and $entries is the whole collection for the "more" list. --}}
<x-layouts.post
    :title="$posts->title"
    :description="$posts->description"
    :category="$posts->category"
    :dateFormatted="$posts->dateFormatted"
    :readTime="$posts->readTime"
    :author="$posts->author"
    :authorRole="$posts->authorRole"
    :avatar="$posts->avatar"
    :more="$entries">

    {!! $posts->content !!}

</x-layouts.post>
