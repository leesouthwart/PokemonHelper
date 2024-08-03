<?php

namespace App\Console\Commands;

use App\Jobs\CreateCard;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportCards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:cards {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports cards from a given csv file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fileName = $this->argument('file');
        $filePath = storage_path('app/' . $fileName);

        if (!file_exists($filePath)) {
            $this->error('CSV file does not exist at ' . $filePath);
            return;
        }

        $csv = Reader::createFromPath($filePath, 'r');

        foreach ($csv as $record) {
            $searchTerm = $record[0];
            $url = $record[1];

            // Dispatch the job
            if(!$searchTerm || !$url) {
                $this->error('Search term or URL is missing');
                continue;
            }

            CreateCard::dispatch($searchTerm, $url);
        }

        $this->info('All jobs have been dispatched successfully.');
    }
}
