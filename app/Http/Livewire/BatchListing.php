<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\EbayListing;

class BatchListing extends Component
{
    public EbayListing $listing;
    public $title;
    public $quantity;
    public $price;

    public function mount(EbayListing $listing)
    {
        $this->listing = $listing;
        $this->title = $this->listing->title;
        $this->quantity = $this->listing->quantity;
        $this->price = $this->listing->price;
    }

    public function render()
    {
        return view('livewire.batch-listing', [
            'listing' => $this->listing,
        ]);
    }

    public function updatedTitle()
    {
        $this->listing->title = $this->title;
        $this->listing->save();
    }

    public function updatedQuantity()
    {
        $this->listing->quantity = $this->quantity;
        $this->listing->save();
    }

    public function updatedPrice()
    {
        $this->listing->price = $this->price;
        $this->listing->save();
    }

    public function checkPrice()
    {
        $service = new \App\Services\EbayService();
        $price = $service->getEbayDataForPsaListing($this->listing->search_phrase);

        dd($price);
    }
}
