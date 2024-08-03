<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Card;

class CardList extends Component
{

    public $cardList = [];

    public function mount()
    {
        $this->cardList = Card::orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.card-list');
    }

}
