{{--<div wire:click="selectCard" class="w-full bg-gray-500 flex items-center px-3 py-2 my-3 cursor-pointer">--}}
{{--    <div class="card_image">--}}
{{--        <img height="80px" width="80px" src="{{$card->image_url}}" alt="Pokemon Card">--}}
{{--    </div>--}}

{{--    <div class="card_title w-1/3 mx-3">--}}
{{--        <input type="text" class="w-full bg-gray-600 text-gray-300" wire:model="searchTerm" value="{{$searchTerm}}">--}}
{{--    </div>--}}

{{--    <div class="card_profit w-1/3 mr-3">--}}
{{--        <div class="w-full bg-gray-600 flex p-2 grid grid-cols-3">--}}
{{--            <p class="text-gray-300 text-left">{{$currency->symbol}}{{$card->converted_price}}</p>--}}
{{--            <p class="text-gray-300 text-center">{{$currency->symbol}}{{$card->psa_10_price}}</p>--}}
{{--            <p class="text-gray-300 text-right">{{$currency->symbol}}{{$card->average_psa_10_price}}</p>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="card_roi w-1/3 mr-3">--}}
{{--        <div class="w-full bg-gray-600 flex p-2 grid grid-cols-2">--}}
{{--            <p class="text-gray-300 text-center">{{$card->roi_lowest}}%</p>--}}
{{--            <p class="text-gray-300 text-center">{{$card->roi_average}}%</p>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<tr wire:click="selectCard">
    <td class="py-4 pl-4 pr-8 sm:pl-6 lg:pl-8">
        <div class="flex items-center gap-x-4">
{{--            <img src="{{$card->image_url}}" alt="" class="h-24 w-24 bg-gray-800">--}}
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
            <div class="font-mono text-sm leading-6 text-gray-400">{{$card->roi_lowest}}%</div>
        </div>
    </td>
    <td class="hidden py-4 pl-0 pr-4 sm:table-cell sm:pr-8">
        <div class="flex gap-x-3">
            <div class="font-mono text-sm leading-6 text-gray-400">{{$card->roi_average}}%</div>
        </div>
    </td>
</tr>
