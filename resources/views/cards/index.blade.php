<x-app-layout class="flex">
    <div class="flex">
        <div class="bg-gray-900 w-4/5">
            <div class="border-b-2 border-gray-700">
                <livewire:card-form />
            </div>

            <div>
                <livewire:card-list />
            </div>
        </div>


        <div class="w-1/5 bg-gray-900 border-l-2 border-gray-700">
            <livewire:sidebar />
        </div>
    </div>
</x-app-layout>
