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

        if($existing) {
            $existing->quantity = $existing->quantity + 1;
            $existing->save();
        } else {
            EbayListing::create([
                'batch_id' => $this->batch->id,
                'title' => $data['title'],
                'price' => null,
                'quantity' => $data['quantity'],
                'image_1' => $data['image1'],
                'image_2' => $data['image2'],
            ]);
        }

        event(new JobCompleted());
    }
}
