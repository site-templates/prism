@props([
    'workspace' => 'Acme AI',
    'initials' => 'AA',
    'colTime' => 'Time',
    'colModel' => 'Model',
    'colTask' => 'Task',
    'colStatus' => 'Status',
    'colLatency' => 'Latency',
    'colCost' => 'Cost',
    'askPlaceholder' => 'Ask about a request…',
    'askLabel' => 'Ask the router',
])
<!--
    The router's own screen, drawn in markup — no image, no screenshot. A
    title bar, then one panel per task: the sidebar (its sections from the
    consoleGroups collection, its links from consoleNav, the lit one named by
    the task), the breadcrumb, the filter row, the request table (its rows are
    the workbenchRows entries carrying that task's key) and the assistant
    panel that says what it ran and what it found. Every one of those
    collections is read by name — no attribute threads them in — so editing a
    row in the builder writes straight back to its file. The tab strip above
    the window swaps the panels; without JavaScript the first one shows.
-->
<div class="win">

    <div class="win-bar">
        <span class="flex gap-1.5" aria-hidden="true">
            <span class="block size-2.5 rounded-full bg-line-strong"></span>
            <span class="block size-2.5 rounded-full bg-line-strong"></span>
            <span class="block size-2.5 rounded-full bg-line-strong"></span>
        </span>
        <span class="flex items-center gap-2 rounded-lg bg-surface px-2.5 py-1 text-xs font-medium text-ink shadow-[inset_0_0_0_1px_var(--color-line)]">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-3.5 shrink-0 opacity-70" aria-hidden="true">
                <rect x="3" y="4" width="18" height="7" rx="2"/><rect x="3" y="13" width="18" height="7" rx="2"/><path d="M7 7.5h.01M7 16.5h.01"/>
            </svg>
            api.prism.example
        </span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" class="size-3.5 shrink-0 text-faint" aria-hidden="true">
            <path d="M12 5v14M5 12h14"/>
        </svg>
    </div>

    @foreach ($workbench as $tab)
    @if ($loop->first)
    <div data-tab-of="{{ $tab->key }}">
    @else
    <div data-tab-of="{{ $tab->key }}" hidden>
    @endif
        <div class="grid grid-cols-[minmax(0,1fr)] lg:grid-cols-[11rem_minmax(0,1fr)]">

            <aside class="win-side max-lg:hidden" aria-label="Console sections">
                <div class="flex items-center gap-2 border-b border-line px-3 py-3">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-md bg-accent text-[0.625rem] font-semibold text-accent-ink">{{ $initials }}</span>
                    <span class="min-w-0 truncate text-[0.8125rem] font-medium text-ink">{{ $workspace }}</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" class="ml-auto size-3.5 shrink-0 text-faint" aria-hidden="true">
                        <path d="m8 9 4-4 4 4M8 15l4 4 4-4"/>
                    </svg>
                </div>
                <div class="flex flex-col gap-5 p-2.5">
                    @foreach ($consoleGroups as $group)
                    <div class="flex flex-col gap-1">
                        <p class="win-group px-2 pb-1">{{ $group->label }}</p>
                        @foreach ($consoleNav as $item)
                        @if ($item->group == $group->label)
                        @if ($item->label == $tab->navActive)
                        <a href="#router" aria-current="page" class="win-link">{{ $item->label }}</a>
                        @else
                        <a href="#router" class="win-link">{{ $item->label }}</a>
                        @endif
                        @endif
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </aside>

            <div class="min-w-0">

                <div class="flex items-center gap-2 border-b border-line px-4 py-2.5 text-xs text-muted">
                    <span class="truncate font-medium text-ink">{{ $workspace }}</span>
                    <span class="text-faint" aria-hidden="true">/</span>
                    <span class="truncate">{{ $tab->crumbTwo }}</span>
                    <span class="text-faint" aria-hidden="true">/</span>
                    <span class="truncate">{{ $tab->crumbThree }}</span>
                    <span class="ml-auto flex shrink-0 items-center gap-3 text-faint">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="size-3.5" aria-hidden="true">
                            <path d="M3 12a9 9 0 0 1 15-6.7L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-15 6.7L3 16"/><path d="M3 21v-5h5"/>
                        </svg>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-3.5 max-sm:hidden" aria-hidden="true">
                            <rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h8"/>
                        </svg>
                    </span>
                </div>

                <div class="grid grid-cols-[minmax(0,1fr)] lg:grid-cols-[minmax(0,1fr)_17rem]">

                    <div class="min-w-0">
                        <div class="flex items-center gap-3 border-b border-line px-4 py-2.5">
                            <span class="flex items-center gap-1.5 rounded-md bg-well px-2 py-1 text-xs text-muted shadow-[inset_0_0_0_1px_var(--color-line)]">
                                {{ $tab->filterLabel }}
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" class="size-3 shrink-0 text-faint" aria-hidden="true">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </span>
                            <span class="num text-xs text-faint">{{ $tab->countLabel }}</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[23rem] text-left text-xs sm:min-w-[34rem]">
                                <thead>
                                    <tr class="border-b border-line text-faint">
                                        <th scope="col" class="py-2 pr-4 pl-4 font-medium whitespace-nowrap">{{ $colTime }}</th>
                                        <th scope="col" class="py-2 pr-4 font-medium whitespace-nowrap">{{ $colModel }}</th>
                                        <th scope="col" class="py-2 pr-4 font-medium whitespace-nowrap max-sm:hidden">{{ $colTask }}</th>
                                        <th scope="col" class="py-2 pr-4 font-medium whitespace-nowrap">{{ $colStatus }}</th>
                                        <th scope="col" class="py-2 pr-4 font-medium whitespace-nowrap max-sm:hidden">{{ $colLatency }}</th>
                                        <th scope="col" class="py-2 pr-4 font-medium whitespace-nowrap">{{ $colCost }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-line">
                                    @foreach ($workbenchRows as $row)
                                    @if ($row->tab == $tab->key)
                                    <tr>
                                        <td class="num py-2.5 pr-4 pl-4 whitespace-nowrap text-faint">{{ $row->time }}</td>
                                        <td class="data py-2.5 pr-4 whitespace-nowrap text-ink">{{ $row->model }}</td>
                                        <td class="py-2.5 pr-4 whitespace-nowrap text-muted max-sm:hidden">{{ $row->task }}</td>
                                        <td class="py-2.5 pr-4">
                                            <span class="win-status tone-{{ $row->tone }}">{{ $row->status }}</span>
                                        </td>
                                        <td class="num py-2.5 pr-4 whitespace-nowrap text-muted max-sm:hidden">{{ $row->latency }}</td>
                                        <td class="num py-2.5 pr-4 whitespace-nowrap text-muted">{{ $row->cost }}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <aside class="win-panel flex flex-col gap-4 p-4">
                        <p class="win-ask self-end text-pretty">{{ $tab->question }}</p>

                        <div class="flex flex-col gap-2">
                            <p class="win-run">
                                <span class="shrink-0 text-faint" aria-hidden="true">&gt;_</span>
                                <span class="min-w-0 truncate">{{ $tab->commandOne }}</span>
                                <span class="num ml-auto shrink-0 text-faint">{{ $tab->commandOneTime }}</span>
                            </p>
                            <p class="win-run">
                                <span class="shrink-0 text-faint" aria-hidden="true">&gt;_</span>
                                <span class="min-w-0 truncate">{{ $tab->commandTwo }}</span>
                                <span class="num ml-auto shrink-0 text-faint">{{ $tab->commandTwoTime }}</span>
                            </p>
                        </div>

                        <p class="text-[0.8125rem]/5 text-pretty text-muted">{{ $tab->answer }}</p>

                        <form class="relative mt-auto pt-2" action="/docs/quickstart" method="get">
                            <label for="ask-{{ $tab->key }}" class="sr-only">{{ $askLabel }}</label>
                            <input id="ask-{{ $tab->key }}" name="prompt" type="text" placeholder="{{ $askPlaceholder }}" class="win-input">
                            <button type="submit" aria-label="{{ $askLabel }}" class="btn-solid absolute right-2 bottom-2 size-7 rounded-md p-0">
                                <svg viewBox="0 0 16 16" class="size-3.5 fill-current" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8 14a.75.75 0 0 1-.75-.75V4.56L4.03 7.78a.75.75 0 0 1-1.06-1.06l4.5-4.5a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06L8.75 4.56v8.69A.75.75 0 0 1 8 14Z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </form>
                    </aside>

                </div>
            </div>

        </div>
    </div>
    @endforeach
</div>
