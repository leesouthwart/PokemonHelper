<div class="bg-gray-900 py-10">
    <table class="mt-6 w-full whitespace-nowrap text-left">
        <colgroup>
            <col class="w-full sm:w-4/12">
            <col class="lg:w-4/12">
            <col class="lg:w-2/12">
            <col class="lg:w-1/12">
            <col class="lg:w-1/12">
        </colgroup>
        <thead class="border-b border-white/10 text-sm leading-6 text-white">
        <tr>
            <th scope="col" class="py-2 pl-4 pr-8 font-semibold sm:pl-6 lg:pl-8">Card Name</th>
            <th scope="col" class="hidden py-2 pl-0 pr-8 font-semibold sm:table-cell">Raw Price</th>
            <th scope="col" class="py-2 pl-0 pr-4 text-right font-semibold sm:pr-8 sm:text-left lg:pr-20">PSA 10 (lowest)</th>
            <th scope="col" class="hidden py-2 pl-0 pr-8 font-semibold md:table-cell lg:pr-20">PSA 10 (average)</th>
            <th scope="col" class="hidden py-2 pl-0 pr-4 text-left font-semibold sm:table-cell sm:pr-6 lg:pr-8">ROI (lowest)</th>
            <th scope="col" class="hidden py-2 pl-0 pr-4 text-right font-semibold sm:table-cell sm:pr-6 lg:pr-8">ROI (average)</th>
            <th scope="col" class="hidden py-2 pl-0 pr-4 text-right font-semibold sm:table-cell sm:pr-6 lg:pr-8">Actions</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-white/5">

        @foreach($cardList as $card)
            <livewire:card :card="$card" :wire:key="$card->id"/>
        @endforeach

        </tbody>
    </table>

    <div class="mt-6 px-8">
        {{ $cardList->links() }}
    </div>

</div>
