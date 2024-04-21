<?php

namespace App\Http\Livewire;

use App\Models\Card;
use Livewire\Component;
use App\Services\EbayService;

class CardForm extends Component
{
    public String $url;
    public String $searchTerm;
    public Bool $loading = false;
    protected $ebayService;

    public function __construct()
    {
        $this->ebayService = new EbayService();
    }

    public function render()
    {
        return view('livewire.card-form');
    }

    public function addCard()
    {
        $this->loading = true;

        try {
            $card = new Card;

            $data = $card->getCardDataFromCr($this->url);

            $card->search_term = $this->searchTerm;
            $card->url = $this->url;
            $card->cr_price = $data['cr_price'];
            $card->image_url = $data['image_url'];
            $card->save();

            $this->ebayService->getEbayData($this->searchTerm);

        } catch (\Exception $e) {
            dd('Error: ' . $e->getMessage());
        }

        $this->resetVars();
    }

    public function resetVars()
    {
        $this->url = '';
        $this->searchTerm = '';
        $this->loading = false;
    }
}
