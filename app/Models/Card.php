<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Client;
use DOMDocument;
use DOMXPath;

class Card extends Model
{
    use HasFactory;

    public $fillable = [
        'search_term',
        'url',
        'psa_10_price',
        'price',
        'image_url',
    ];



    public function getCardDataFromCr($url)
    {
        $httpClient = new Client();

        $response = $httpClient->get($url);

        $htmlString = (string) $response->getBody();

        // HTML is often wonky, this suppresses a lot of warnings
        libxml_use_internal_errors(true);

        $doc = new DOMDocument();
        $doc->loadHTML($htmlString);

        $xpath = new DOMXPath($doc);

        $price = $xpath->evaluate('//span[@id="pricech"][1]');

        $data = [
            'cr_price' => str_replace('円', '', $price->item(0)->nodeValue),
            'image_url' => $xpath->evaluate('//img[@id="main_img_1"]/@src')->item(0)->nodeValue
        ];

        return $data;
    }

    public function getConvertedPriceAttribute()
    {
        $currency = Currency::where('code', session('currency'))->first();
        $intVal = intval(str_replace(',', '', $this->cr_price));
        $price = $intVal * $currency->conversionsTo->where('id', Currency::JPY)->first()->pivot->conversion_rate;

        return number_format($price, 2, '.', '');
    }
}
