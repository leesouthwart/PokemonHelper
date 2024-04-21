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
        // FOR LIVE
//        $client = new \GuzzleHttp\Client();
//
//        $response = $client->request('GET', config('settings.scrape_url_base') . $url, [
//            'headers' => [
//                'accept' => 'application/json',
//            ],
//        ]);
//
//
//        $json = json_decode($response->getBody()->getContents(), true);
//        $jsonData = $json['result']['selectorElements'];
//
//        dd($jsonData);

        // FOR TESTING
        $jsonData = [
            [
                "selector" => "#main_img_1",
                "textNodes" => [""],
                "htmlElements" => [
                    '<img src="https://www.cardrush-pokemon.jp/data/cardrushpokemon/_/70726f647563742f43525f533132615f37392e6a7067003430300000660023666666666666.jpg" data-x2="https://www.cardrush-pokemon.jp/data/cardrushpokemon/_/70726f647563742f43525f533132615f37392e6a7067003634350000740023666666666666.jpg" width="400" height="400" id="main_img_1" alt="画像1: ミュウ【AR】{183/172}" data-id="64908">'
                ]
            ],
            [
                "selector" => "#pricech",
                "textNodes" => [
                    "780円"
                ],
                "htmlElements" => [
                    "<span class=\"figure\" id=\"pricech\">680円</span>"
                ]
            ]
        ];

        $dom = new DOMDocument();
        $dom->loadHTML($jsonData[0]['htmlElements'][0]);
        $xpath = new DOMXPath($dom);

        $data = [
            'cr_price' => str_replace('円', '', $jsonData[1]['textNodes'][0]),
            'image_url' => $xpath->evaluate("string(//img/@src)")
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
