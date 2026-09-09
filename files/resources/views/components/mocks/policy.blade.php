@props([
    'title' => 'production.policy.yml',
    'footer' => 'Pushed 4 minutes ago · 2,318 requests routed since',
    'rules',
])
<!--
    The policy card: a drawn file of routing rules — a titlebar, then one row
    per rule with the condition on the left and the action on the right, set
    in mono. The rules come from resources/data/collections/policyRules.json.
-->
<div class="frame overflow-hidden">
    <div class="flex items-center gap-3 border-b border-line px-5 py-3">
        <x-mocks.lights />
        <p class="data text-muted">{{ $title }}</p>
        <span class="ml-auto inline-flex items-center gap-1.5 rounded-full bg-accent-wash px-2 py-0.5 text-xs font-medium text-accent">
            <span class="live-dot" aria-hidden="true"></span>
            live
        </span>
    </div>
    <ol role="list" class="divide-y divide-line">
        @foreach ($rules as $rule)
        <li class="grid gap-x-6 gap-y-1 px-5 py-4 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
            <p class="data text-ink"><span class="text-diverted">when</span> {{ $rule->when }}</p>
            <p class="data text-ink"><span class="text-accent">then</span> {{ $rule->then }}</p>
        </li>
        @endforeach
    </ol>
    <p class="num border-t border-line bg-well px-5 py-3 text-xs text-faint">{{ $footer }}</p>
</div>
