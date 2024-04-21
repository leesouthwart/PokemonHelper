<div>
    @foreach($cardList as $card)
        <livewire:card :card="$card" wire:key="$card->id"/>
    @endforeach
</div>
