<?php

namespace App\Services;

use App\Services\AccessTokenService;

use Illuminate\Support\Facades\Http;
use App\Models\Card;

class EbayService
{
   public $accessToken;


   public function __construct()
   {
       $this->accessToken = (new AccessTokenService)->getAccessToken();
   }

   // Get data from eBay API. Called when Cards are first submitted and when they are focused on. Gets most up to date data and updates the card.
    public function getEbayData($searchTerm)
    {
        // @todo figure out a better way to always have PSA 10 in the search term WITHOUT saving it in DB (as this would change front end forms)
        $searchTermEbay = $searchTerm . ' PSA 10';

        $response = Http::withHeaders([
            'X-EBAY-C-MARKETPLACE-ID' => 'EBAY_GB',
            'X-EBAY-C-ENDUSERCTX' => 'contextualLocation=country%3DUK%2Czip%3DLE77JG',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->get('https://api.ebay.com/buy/browse/v1/item_summary/search?q=' . $searchTermEbay .'&limit=3&sort=price&filter=itemLocationCountry:GB');

        $data = $response->json();

        $itemCardPrice = 0;

        foreach ($data['itemSummaries'] as $item) {
            $items[] = [
                'title' => $item['title'],
                'price' => $item['price']['value'],
                'image' => $item['image']['imageUrl'],
                'url' => $item['itemWebUrl'],
                'seller' => $item['seller'],
            ];

            $itemCardPrice += $item['price']['value'];
            $itemCardPrice += $item['shippingOptions'][0]['shippingCost']['value'] ?? 0;
        }

        $averageItemCardPrice = number_format($itemCardPrice / count($data['itemSummaries']), 2);
        $lowestItemCardPrice = min(array_column($items, 'price'));

        $card = Card::where('search_term', $searchTerm)->first();

        if($card) {
            $card->psa_10_price = $lowestItemCardPrice;
            $card->average_psa_10_price = $averageItemCardPrice;

            $card->roi_lowest = $this->calcRoi($card->converted_price, $lowestItemCardPrice);
            $card->roi_average = $this->calcRoi($card->converted_price, $averageItemCardPrice);

            $card->save();
        } else {
            Log::error('Card not found for ' . $searchTerm);
        }

        return $items;
    }

    public function getEbayDataForPsaListing($searchTerm)
    {
        $search = app()->environment('local') ? 'car' : $searchTerm;
        $response = Http::withHeaders([
            'X-EBAY-C-MARKETPLACE-ID' => 'EBAY_GB',
            'X-EBAY-C-ENDUSERCTX' => 'contextualLocation=country%3DUK%2Czip%3DLE77JG',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->get('https://api.ebay.com/buy/browse/v1/item_summary/search?q=' . $search .'&limit=3&sort=price&filter=itemLocationCountry:GB');

        $data = $response->json();

        $itemCardPrice = 0;

        if(isset($data['itemSummaries'])) {
            foreach ($data['itemSummaries'] as $item) {
                $items[] = [
                    'price' => $item['price']['value'] + $item['shippingOptions'][0]['shippingCost']['value'] ?? 0,
                ];

                $itemCardPrice += $item['price']['value'];
                $itemCardPrice += $item['shippingOptions'][0]['shippingCost']['value'] ?? 0;
            }

            $averageItemCardPrice = number_format($itemCardPrice / 3, 2);
            $lowestItemCardPrice = min(array_column($items, 'price'));

            return [
                'lowest' => $lowestItemCardPrice,
                'average' => $averageItemCardPrice,
            ];
        }

        return [
            'lowest' => 0.00,
            'average' => 0.00
        ];
    }

    public function calcRoi($price, $price2)
    {
        // Calculate $afterFees
        $afterFees = $price2 - (0.155 * $price2); // Subtract 15.5% of $price2

        // Check if $price2 is greater than 30
        if ($price2 > 30) {
            $afterFees -= 3; // Subtract 3 if $price2 is greater than 30
        } else {
            $afterFees -= 1.75; // Subtract 2 if $price2 is 30 or less
        }

        // Calculate the adjusted initial price
        $initialPrice = $price + 13;

        // Calculate ROI
        // ROI formula: ((Final Value - Initial Value) / Initial Value) * 100
        $roi = (($afterFees - $initialPrice) / $initialPrice) * 100;

        return $roi;
    }

    //@todo - This isnt working because we don't have permission to use the marketplace_insights api
    // https://developer.ebay.com/api-docs/buy/marketplace-insights/overview.html
    public function getSalesData($searchTerm)
    {
        $response = Http::withHeaders([
            'X-EBAY-C-MARKETPLACE-ID' => 'EBAY_GB',
            'X-EBAY-C-ENDUSERCTX' => 'contextualLocation=country%3DUK%2Czip%3DLE77JG',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->get('https://api.ebay.com/buy/marketplace_insights/v1_beta/item_sales/search?q=iphone&category_ids=9355&limit=3');

        return $response->json();
    }
}
