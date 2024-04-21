<div wire:click="selectCard" class="w-full bg-gray-500 flex items-center px-3 py-2 my-3 cursor-pointer">
    <div class="card_image">
        <img height="80px" width="80px" src="{{$card->image_url}}" alt="Pokemon Card">
    </div>

    <div class="card_title w-1/3 mx-3">
        <input type="text" class="w-full bg-gray-600 text-gray-300" wire:model="searchTerm" value="{{$searchTerm}}">
    </div>

    <div class="card_profit w-1/3 mr-3">
        <div class="w-full bg-gray-600 flex p-2 grid grid-cols-3">
            <p class="text-gray-300 text-left">{{$currency->symbol}}{{$card->converted_price}}</p>
            <p class="text-gray-300 text-center">{{$currency->symbol}}{{$card->converted_price}}</p>
            <p class="text-gray-300 text-right">{{$currency->symbol}}{{$card->converted_price}}</p>
        </div>
    </div>
</div>
