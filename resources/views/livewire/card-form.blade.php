<div class="w-full bg-gray-500 flex items-center px-3 py-2 my-3">
    <div class="form-container w-1/3">
        <input wire:model="searchTerm" id="searchTerm" type="text" class="w-full bg-gray-600 text-gray-300" placeholder="Add card search phrase...">
    </div>

    <div class="card_url w-1/3 mx-3">
        <input type="text" class="w-full bg-gray-600 text-gray-300" wire:model="url" placeholder="Enter Url">
    </div>

    <div class="button_container">
        <button type="button" wire:click="addCard" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Card</button>
    </div>
</div>
