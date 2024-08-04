
<tr class="cursor-pointer" wire:click="selectCard">
    <td class="py-4 pl-4">
        <div class="flex items-center">
            <img src="{{$card->image_url}}" class="aspect-[4/5] h-20">
        </div>
    </td>

    <td class="py-4 pl-4 pr-8 sm:pl-6 lg:pl-8">
        <div class="flex items-center">
            <div class="truncate text-sm font-medium leading-6 text-white">{{$card->search_term}}</div>
        </div>
    </td>
    <td class="hidden py-4 pl-0 pr-4 sm:table-cell sm:pr-8">
        <div class="flex gap-x-3">
            <div class="font-mono text-sm leading-6 text-gray-400">{{$currency->symbol}}{{$card->converted_price}}</div>
        </div>
    </td>
    <td class="hidden py-4 pl-0 pr-4 sm:table-cell sm:pr-8">
        <div class="flex gap-x-3">
            <div class="font-mono text-sm leading-6 text-gray-400">{{$currency->symbol}}{{$card->psa_10_price}}</div>
        </div>
    </td>
    <td class="hidden py-4 pl-0 pr-4 sm:table-cell sm:pr-8">
        <div class="flex gap-x-3">
            <div class="font-mono text-sm leading-6 text-gray-400">{{$currency->symbol}}{{$card->average_psa_10_price}}</div>
        </div>
    </td>
    <td class="hidden py-4 pl-0 pr-4 sm:table-cell sm:pr-8">
        <div class="flex gap-x-3">
            <div class="font-mono text-sm leading-6 {{$roiLowestColor}}">{{$card->roi_lowest}}%</div>
        </div>
    </td>
    <td class="hidden py-4 pl-0 pr-4 sm:table-cell sm:pr-8">
        <div class="flex gap-x-3">
            <div class="font-mono text-sm leading-6 {{$roiAverageColor}}">{{$card->roi_average}}%</div>
        </div>
    </td>

    <td class="hidden py-4 pl-0 pr-4 sm:table-cell sm:pr-8">
        <a target="_blank" href="{{$card->url}}">
            <div class="font-mono text-sm leading-6 text-gray-400">
                <i class="fas fa-external-link-alt"></i>
            </div>
        </a>
    </td>
</tr>
