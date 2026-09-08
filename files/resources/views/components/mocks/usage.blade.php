@props([
    'title' => 'Spend by model',
    'period' => 'September, to date',
    'total' => '$4,322',
    'delta' => '−31% vs. August at the same point',
    'bars',
])
<!--
    The usage chart: a titlebar with the month's total, then one bar per model
    growing from the baseline to its share, with the model and its spend under
    it. Bars come from resources/data/collections/usageBars.json; the height is
    a percentage the CSS reads as --h.
-->
<div class="frame overflow-hidden">
    <div class="flex flex-wrap items-end justify-between gap-4 px-5 pt-5 pb-4">
        <div>
            <p class="text-sm font-medium text-ink">{{ $title }}</p>
            <p class="mt-0.5 text-xs text-faint">{{ $period }}</p>
        </div>
        <div class="text-right">
            <p class="display-stat font-display text-ink">{{ $total }}</p>
            <p class="num mt-1 text-xs text-accent">{{ $delta }}</p>
        </div>
    </div>
    <div class="px-5 pb-5">
        <ol role="list" class="grid h-44 grid-cols-6 items-end gap-3 border-b border-line">
            @foreach ($bars as $bar)
            <li class="flex h-full flex-col justify-end">
                <span class="bar block w-full rounded-t-sm bg-accent [--h:{{ $bar->height }}]" aria-hidden="true"></span>
            </li>
            @endforeach
        </ol>
        <ol role="list" class="mt-3 grid grid-cols-6 gap-3">
            @foreach ($bars as $bar)
            <li class="min-w-0">
                <p class="num truncate text-xs font-medium text-ink">{{ $bar->spend }}</p>
                <p class="truncate text-xs text-faint">{{ $bar->model }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</div>
