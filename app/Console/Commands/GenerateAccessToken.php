<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AccessTokenService;

class GenerateAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:access_token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating Token...');
        $accessTokenService = new AccessTokenService();
        $token = $accessTokenService->getAccessToken();

        if($token) {
            $this->info('Token generated successfully! ' . $token);
        }

        return 1;
    }
}
