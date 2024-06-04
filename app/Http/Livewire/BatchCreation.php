<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use App\Exports\EbayListingExport;
use Maatwebsite\Excel\Facades\Excel;

class BatchCreation extends Component
{

    public string $start = '';
    public string $end = '';
    public $batch;
    public $listings;
    public bool $loading = false;

    protected $listeners = ['echo:jobs,JobCompleted' => 'handleListingDone'];

    public function mount()
    {
        $this->batch = \App\Models\Batch::find(2);
        $this->listings = $this->batch->ebayListings ?? null;
    }

    public function render()
    {
        return view('livewire.batch-creation');
    }

    public function submit()
    {
        $this->batch = \App\Models\Batch::create([
            'name' => Carbon::now() . '_' . uniqid()
        ]);

        $this->loading = true;
        for ($i = $this->start; $i <= $this->end; $i++) {
            \App\Jobs\CreateEbayListing::dispatch($i, $this->batch);
        }
    }

    public function handleListingDone()
    {
        // Refresh the listings
        $this->listings = $this->batch->ebayListings;

        if(Queue::size() == 0) {
            $this->loading = false;
        }
    }

    public function export()
    {
        return Excel::download(new EbayListingExport($this->batch), 'listings.xlsx');
    }
}
