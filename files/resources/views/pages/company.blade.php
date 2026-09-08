<!-- The company page: the story, the principles, the open roles. -->
<x-layouts.main title="Company" description="Prism is a small team in Berlin and remote building the model router we could not buy." current="/company">

    <x-sections.page-header heading="A small team in front of every model." body="Prism is fourteen people who think a router should be boring, auditable and paid for honestly." />

    <x-sections.story />

    <x-sections.principles :items="$principles" />

    <x-sections.roles :items="$roles" />

</x-layouts.main>
