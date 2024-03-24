<?php

namespace App\Http\Livewire;

use App\Models\Card;
use Livewire\Component;

class CardForm extends Component
{
    public String $url;
    public String $searchTerm;
    public Bool $loading = false;

    public function render()
    {
        return view('livewire.card-form');
    }

    public function addCard()
    {
        $loading = true;

        try {
            $card = new Card;

            $data = $card->getCardDataFromCr($this->url);

            $card->search_term = $this->searchTerm;
            $card->url = $this->url;
            $card->cr_price = $data['cr_price'];
            $card->image_url = $data['image_url'];
            $card->save();
        } catch (\Exception $e) {
            dd('Error: ' . $e->getMessage());
        }

        $loading = false;
    }
}
