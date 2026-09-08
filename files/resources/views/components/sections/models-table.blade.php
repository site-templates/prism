@props([
    'note' => 'Prices are per million tokens at list rate, per provider. Latency is median time to first token over the last hour. Quality is the pool score on our benchmark traffic.',
    'colModel' => 'Model',
    'colContext' => 'Context',
    'colInput' => 'Input',
    'colOutput' => 'Output',
    'colLatency' => 'Latency',
    'colQuality' => 'Quality',
    'families',
    'items',
])
<!--
    The catalog: a row of filter chips, then one table of every model in the
    pool — name and id, context window, input and output price, latency, the
    quality bar. Pressing a chip hides the rows whose family doesn't match
    (main.js); the first chip shows all. Chips come from families, rows from
    models. The table sits directly on the canvas with hairline rows only,
    and scrolls sideways on phones.
-->
<section class="pb-16 md:pb-24">
    <div class="mx-auto max-w-page px-6">
        <div class="flex flex-wrap gap-2" data-filter>
            @foreach ($families as $family)
            @if ($loop->first)
            <button type="button" class="chip" aria-pressed="true" data-filter-key="{{ $family->key }}">{{ $family->label }}</button>
            @else
            <button type="button" class="chip" aria-pressed="false" data-filter-key="{{ $family->key }}">{{ $family->label }}</button>
            @endif
            @endforeach
        </div>

        <div class="-mx-6 mt-8 overflow-x-auto whitespace-nowrap">
            <div class="inline-block min-w-full px-6 align-middle">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-strong text-faint">
                            <th scope="col" class="py-3 pr-6 font-medium whitespace-nowrap">{{ $colModel }}</th>
                            <th scope="col" class="py-3 pr-6 font-medium whitespace-nowrap">{{ $colContext }}</th>
                            <th scope="col" class="py-3 pr-6 font-medium whitespace-nowrap">{{ $colInput }}</th>
                            <th scope="col" class="py-3 pr-6 font-medium whitespace-nowrap">{{ $colOutput }}</th>
                            <th scope="col" class="py-3 pr-6 font-medium whitespace-nowrap">{{ $colLatency }}</th>
                            <th scope="col" class="py-3 font-medium whitespace-nowrap">{{ $colQuality }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($items as $model)
                        <tr class="table-row" data-family="{{ $model->family }}">
                            <td class="py-4 pr-6">
                                <a href="/docs/quickstart" class="group block">
                                    <span class="block font-medium text-ink group-hover:text-accent">{{ $model->name }}</span>
                                    <span class="data block text-faint">{{ $model->id }}</span>
                                </a>
                            </td>
                            <td class="num py-4 pr-6 text-muted">{{ $model->context }}</td>
                            <td class="num py-4 pr-6 text-muted">{{ $model->inputPrice }}</td>
                            <td class="num py-4 pr-6 text-muted">{{ $model->outputPrice }}</td>
                            <td class="num py-4 pr-6 text-muted">{{ $model->latency }}</td>
                            <td class="py-4">
                                <span class="flex items-center gap-3">
                                    <span class="score [--score:{{ $model->score }}%]" aria-hidden="true"></span>
                                    <span class="num w-6 text-ink">{{ $model->score }}</span>
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <p class="mt-6 max-w-[70ch] text-sm text-pretty text-faint">{{ $note }}</p>
    </div>
</section>
