<div class="flex" wire:click="checkPrice">
    <div class="w-full m-2">
        <input class="w-full" type="text" wire:model="title" value="{{$listing->title}}">
    </div>

    <div class="w-1/3 m-2">
        <input class="w-full" type="text" wire:model="quantity">
    </div>

    <div class="w-1/3 m-2">
        <input class="w-full" type="text" wire:model="price" placeholder="Enter Price">
    </div>
</div>
