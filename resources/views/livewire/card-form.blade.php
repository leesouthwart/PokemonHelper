<div class="w-full bg-gray-900 flex items-center px-3 py-2 my-3">
    <div class="mt-2 flex rounded-md shadow-sm mx-3">
        <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 px-3 text-gray-500 sm:text-sm">PSA 10</span>
        <input wire:model="searchTerm" id="searchTerm" type="text" class="block w-full min-w-0 flex-1 rounded-none rounded-r-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="magikarp 080 073">
    </div>

    <div class="mt-2 flex rounded-md shadow-sm mr-3">
        <label for="url" class="sr-only">Url</label>
        <input type="email" id="url" name="url" wire:model="url" placeholder="Enter Url" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
    </div>

    <div class="button_container mt-2">
        <button type="button" wire:click="addCard" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">Add Card</button>
    </div>
</div>
