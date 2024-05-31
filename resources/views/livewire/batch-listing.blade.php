<div class="flex">
    <div class="w-full m-2">
        <input class="w-full" type="text" wire:model="title" value="{{$listing->title}}">
    </div>

    <div class="w-1/3 m-2">
        <input class="w-full" type="text" wire:model="quantity">
    </div>

    <div class="w-1/3 m-2 relative">
        <input class="w-full" type="text" wire:model="price" placeholder="Enter Price">
        <div class="absolute top-[14px] right-0">
            <p class="ebay_fee_text text-gray-500">{{$currency->symbol}}{{$afterFees}}</p>
        </div>
    </div>
</div>
