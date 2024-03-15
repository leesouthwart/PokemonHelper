<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use App\Models\Currency;

class FetchCurrencyConversions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:currency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches currency conversion data and populates the currency_conversions table.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currencies = Currency::all();

        foreach($currencies as $currency) {
            $response = Http::get(config('settings.currency_conversion_api_url_base') . $currency->code);

            $json = $response->json();

            $otherCurrencies = Currency::where('id', '!=', $currency->id)->get();

            foreach($otherCurrencies as $target) {
                $conversion = $currency->conversionsFrom()->where('currency_id_2', $target->id)->first();

                if($conversion) {
                    $conversion->pivot->conversion_rate = $json['conversion_rates'][strtoupper($target->code)];
                    $conversion->pivot->save();
                } else {
                    $currency->conversionsFrom()->attach($target->id, ['conversion_rate' => $json['conversion_rates'][strtoupper($target->code)]]);
                }
            }
        }
    }
}
