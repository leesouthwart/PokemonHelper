<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Card extends Component
{
    public $card;
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
}
