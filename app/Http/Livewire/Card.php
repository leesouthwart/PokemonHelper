<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Card as CardModel;

class Card extends Component
{
    public CardModel $card;
    public $searchTerm;
    public $roiLowestColor;
    public $roiAverageColor;

    public $listeners = ['cardUpdated'];


    public function mount($card)
    {
        $this->card = $card;
        $this->searchTerm = $this->card->search_term;
        $this->calculateColours();
    }
    public function render()
    {
        return view('livewire.card');
    }

    public function selectCard()
    {
        $this->emit('cardSelected', $this->card->id);
    }

    private function calculateColours()
    {
        $lowest = $this->card->roi_lowest;
        $average = $this->card->roi_average;
        $colours = [
            'light_green' => 'text-green-400',
            'green' => 'text-green-600',
            'orange' => 'text-yellow-500',
            'red' => 'text-red-500',
        ];

        if ($lowest > 75) {
            $this->roiLowestColor =  $colours['light_green'];
        } elseif ($lowest > 50) {
            $this->roiLowestColor =  $colours['green'];
        } elseif ($lowest > 30) {
            $this->roiLowestColor =  $colours['orange'];
        } else {
            $this->roiLowestColor =  $colours['red'];
        }

        if ($average > 75) {
            $this->roiAverageColor =  $colours['light_green'];
        } elseif ($average > 50 && $average < 75) {
            $this->roiAverageColor =  $colours['green'];
        } elseif ($average > 30 && $average < 50) {
            $this->roiAverageColor =  $colours['yellow'];
        } elseif ($average < 30) {
            $this->roiAverageColor =  $colours['red'];
        }
    }

    public function cardUpdated($card)
    {
        if($this->card->id == $card['id']) {
            $this->mount(CardModel::find($card['id']));
        }
    }
}
