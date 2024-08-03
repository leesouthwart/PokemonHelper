<?php

namespace App\Jobs;

use App\Models\Card;
use App\Services\EbayService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateCard implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $searchTerm;
    public $url;
    public $ebayService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($searchTerm, $url)
    {
        $this->searchTerm = $searchTerm;
        $this->url = $url;
        $this->ebayService = new EbayService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $existingCard = Card::where('search_term', $this->searchTerm)->first();

        if ($existingCard) {
            Log::info('Card already exists for ' . $this->searchTerm);
            return;
         }

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
            Log::error('Error: ' . $e->getMessage());
        }
    }
}
