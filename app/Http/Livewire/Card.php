<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Card as CardModel;

class Card extends Component
{
    public CardModel $card;
    public $searchTerm;


    public function mount($card)
    {
        $this->card = $card;
        $this->searchTerm = $this->card->search_term;
    }
    public function render()
    {
        return view('livewire.card');
    }

    public function selectCard()
    {
        $this->emit('cardSelected', $this->card->id);
    }
}
