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
        $search = app()->environment('local') ? 'car' : $searchTerm;
        $response = Http::withHeaders([
            'X-EBAY-C-MARKETPLACE-ID' => 'EBAY_GB',
            'X-EBAY-C-ENDUSERCTX' => 'contextualLocation=country%3DUK%2Czip%3DLE77JG',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->get('https://api.sandbox.ebay.com/buy/browse/v1/item_summary/search?q=' . $search .'&limit=3&sort=price');

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
        }

        $averageItemCardPrice = number_format($itemCardPrice / 3, 2);
        $lowestItemCardPrice = min(array_column($items, 'price'));

        $card = Card::where('search_term', $searchTerm)->first();

        if($card) {
            $card->psa_10_price = $lowestItemCardPrice;
            $card->average_psa_10_price = $averageItemCardPrice;
            $card->save();
        }

        return $items;
    }
}
