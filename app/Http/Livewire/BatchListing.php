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
    public $afterFees;

    public function mount(EbayListing $listing)
    {
        $this->listing = $listing;
        $this->title = $this->listing->title;
        $this->quantity = $this->listing->quantity;
        $this->price = $this->listing->price;
        $this->calcAfterFees();
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
        $this->calcAfterFees();
    }

    public function calcAfterFees()
    {
        $price = $this->price - ($this->price * 0.165);

        if($this->price < 30) {
            $this->afterFees = number_format($price - 2.3, 2);
        } else {
            $this->afterFees = number_format($price - 3.3, 2);
        }
    }
}
