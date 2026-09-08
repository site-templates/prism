@props([
    'heading' => 'Eleven providers. One invoice, at their list price.',
    'items',
])
<!--
    The provider strip under the hero: one line of text, then the provider
    names set as quiet wordmarks in the display face — eight of them, four to
    a row on phones and one row on wide screens, so the rows always balance.
    Names come from the providers collection.
-->
<section class="border-y border-line bg-well py-12">
    <div class="mx-auto max-w-page px-6">
        <p class="text-center text-sm text-faint">{{ $heading }}</p>
        <ul role="list" class="mt-8 grid grid-cols-2 gap-x-6 gap-y-6 sm:grid-cols-4 lg:grid-cols-8">
            @foreach ($items as $item)
            <li class="flex items-center justify-center font-display text-xl text-muted">{{ $item->name }}</li>
            @endforeach
        </ul>
    </div>
</section>
