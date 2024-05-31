<?php

namespace App\Jobs;

use App\Events\JobCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use \App\Models\EbayListing;
use Livewire\Livewire;

class CreateEbayListing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $service;
    public $cert;
    public $batch;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cert, $batch)
    {
        $this->cert = $cert;
        $this->batch = $batch;
        $this->service = new \App\Services\PsaService();
        $this->ebayService = new \App\Services\EbayService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->service->getPsaCardData($this->cert);

        // Check for existing EbayListing - increase quantity if found.
        $existing = EbayListing::filterDupes($this->batch->id, $data['title'])->first();

        // If the input ends in .x0 or .x5, subtract 0.01
        $decimalPart = fmod($data['price'], 1);
        if (substr(number_format($decimalPart, 2, '.', ''), -1) == '0' || substr(number_format($decimalPart, 2, '.', ''), -1) == '5') {
            $data['price'] -= 0.01;
        }

        // Round down to the nearest 5p
        $rounded = floor($data['price'] * 20) / 20;

        // Convert the rounded value to a string to check the last two digits
        $roundedString = number_format($rounded, 2, '.', '');

        // If the result ends in .00, subtract 0.01 to make it end in .99
        if (substr($roundedString, -2) == '00') {
            $rounded -= 0.01;
        }

        $price = number_format($rounded, 2, '.', '');

        if($existing) {
            $existing->quantity = $existing->quantity + 1;
            $existing->save();
        } else {
            EbayListing::create([
                'batch_id' => $this->batch->id,
                'title' => $data['title'],
                'price' => $price,
                'quantity' => $data['quantity'],
                'image_1' => $data['image1'],
                'image_2' => $data['image2'],
                'search_phrase' => $data['search_phrase'],
            ]);
        }

        event(new JobCompleted());
    }
}
