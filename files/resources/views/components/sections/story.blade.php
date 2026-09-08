@props([
    'heading' => 'We built the router we could not buy.',
    'paraOne' => 'In 2025 the three of us were running a support product on four models from three providers, with a spreadsheet deciding which. Every price change meant a deploy. Every outage meant a page at 3 a.m. and a manual switch.',
    'paraTwo' => 'Prism started as the script that replaced the spreadsheet. It became a company when other teams asked for the script. Today it routes requests for a few hundred products, charges a flat fee, and still writes down why every request went where it went.',
    'factOne' => 'Founded 2025',
    'factTwo' => 'Berlin and remote',
    'factThree' => '14 people',
    'factFour' => 'Bootstrapped',
])
<!--
    The company story: a serif heading and two paragraphs on the left, four
    facts in a hairline column on the right.
-->
<section class="pb-16 md:pb-24">
    <div class="mx-auto max-w-page px-6">
        <div class="grid gap-x-12 gap-y-10 lg:grid-cols-[minmax(0,8fr)_minmax(0,4fr)]">
            <div data-reveal>
                <h2 class="display-section max-w-[20ch] font-display text-balance text-ink">{{ $heading }}</h2>
                <p class="lede mt-6 max-w-[56ch] text-pretty text-muted">{{ $paraOne }}</p>
                <p class="mt-5 max-w-[62ch] text-base text-pretty text-muted">{{ $paraTwo }}</p>
            </div>
            <ul role="list" class="grid grid-cols-2 gap-x-6 gap-y-6 border-t border-line pt-6 lg:flex lg:flex-col lg:gap-0 lg:border-t-0 lg:border-l lg:pt-0 lg:pl-8" data-reveal>
                <li class="lg:border-b lg:border-line lg:py-5"><p class="text-base font-medium text-ink">{{ $factOne }}</p></li>
                <li class="lg:border-b lg:border-line lg:py-5"><p class="text-base font-medium text-ink">{{ $factTwo }}</p></li>
                <li class="lg:border-b lg:border-line lg:py-5"><p class="text-base font-medium text-ink">{{ $factThree }}</p></li>
                <li class="lg:py-5"><p class="text-base font-medium text-ink">{{ $factFour }}</p></li>
            </ul>
        </div>
    </div>
</section>
