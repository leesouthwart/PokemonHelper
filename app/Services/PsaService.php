<?php

namespace App\Services;

use App\Services\AccessTokenService;
use Illuminate\Support\Facades\Http;
use App\Models\Card;
use App\Models\Brand;

use App\Services\EbayService;

class PsaService
{
    public function __construct()
    {
        $this->accessToken = (new AccessTokenService)->getAccessToken();
        $this->ebayService = new EbayService();
    }

    public function getPsaCardData($cert)
    {
        $client = new \GuzzleHttp\Client();
        $url = "https://api.psacard.com/publicapi/cert/GetByCertNumber/" . $cert;


        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'bearer ' . config('settings.psa_api_access_token'),
                'Content-Type' => 'application/json',
            ],
        ]);

        $r = $response->getBody()->getContents();

        $result = json_decode($r)->PSACert;

        $mainData = $this->formatMainData($result);

        $images = $this->getImages($cert);

        foreach($images as $image) {
            if($image->IsFrontImage == True) {
                $image1 = $image->ImageURL;
            } else {
                $image2 = $image->ImageURL;
            }
        }

        $title = $this->formatTitle($mainData);
        $searchPhrase = $this->formatTitle($mainData, true);
        $description = $this->formatBodyDescription($image1, $image2, $title);
        $quantity = 1;
        $price = $this->getPrice($searchPhrase);

        return [
            'title' => $title,
            'search_phrase' => $searchPhrase,
            'description' => $description,
            'quantity' => $quantity,
            'image1' => $image1,
            'image2' => $image2,
            'mainData' => $mainData,
            'price' => $price
        ];
    }

    public function formatImage1($image)
    {
        preg_match('/<img\s+src="([^"]+)"/i', $image, $matches);

        if (isset($matches[1])) {
            $image1 = $matches[1];
        }

        return $image1;
    }

    public function formatImage2($image)
    {
        preg_match('/<img\s+src="([^"]+)"/i', $image, $matches);

        if (isset($matches[1])) {
            $image = $matches[1];
        }

        return $image;
    }

    public function formatMainData($data)
    {
        return [
            'brand' => $data->Brand,
            'cardNumber' => $data->CardNumber,
            'pokemon' => $data->Subject,
            'grade' => $data->CardGrade,
            'pedigree' => $data->Variety,
        ];
    }

    public function formatTitle($mainData, $forSearch = false)
    {
        $title = [];
        $title['grade'] = config('psa.grade_labels')[$mainData['grade']];
        $title['pokemon'] = $this->stripTitle($mainData['pokemon'], $mainData['pedigree']);

        // @todo reminder - fall back if brand doesnt exist
        $brand = Brand::where('psa_name', $mainData['brand'])->first();

        if($brand) {
            $title['numbers'] = $this->setNumbers($brand->set_numbers, $mainData['cardNumber']);
            $title['set'] = $brand->friendly_label;
        } else {
            $title['numbers'] = $mainData['cardNumber'];
            $title['set'] = $mainData['brand'];
        }

        $title['description'] = $this->setDescription($mainData);

        if($forSearch) {
            return 'PSA ' . $title['grade'] . ' ' . $title['pokemon'] . ' ' . $title['numbers'];
        }

        return 'PSA ' . $title['grade'] . ' ' . $title['pokemon'] . ' ' . $title['numbers'] . ' ' . $title['set'] . ' ' . $title['description'];
    }

    public function stripTitle($pokemonName, $pedigree)
    {
        // Strip away unneeded waffle in titles
        if (strpos($pokemonName, '/') !== false) {
            $parts = explode('/', $pokemonName);
            $pokemonName = $parts[1];
        }

        if (strpos($pokemonName, '-') !== false) {
            $parts = explode('-', $pokemonName);
            $pokemonName = $parts[0];
        }

        $lowercaseString = strtolower($pokemonName);
        $finalString = ucwords($lowercaseString);

        // Pedigree matching
        $finalString .= match ($pedigree) {
            'REVERSE HOLO' => ' Reverse Holo',
            'MASTER BALL REVERSE HOLO' => ' Master Ball Reverse',
            'ART RARE' => ' AR',
            default => '',
        };

        return $finalString;
    }

    public function setNumbers($brandNo, $cardNumber)
    {
        if ($brandNo) {
            return $cardNumber . '/' . $brandNo;
        }

        return $cardNumber;
    }

    public function setDescription($mainData)
    {
        $prefix = match ($mainData['grade']) {
            'GEM MT 10' => 'GEM MINT ',
            'MINT 9' => 'MINT ',
            default => '',
        };

        $prefix .= str_contains($mainData['brand'], 'JAPANESE') ? 'Japanese ' : ' ';


        return $prefix . 'Pokemon Card';
    }

    // Ebay Listing Description
    public function formatBodyDescription($image1, $image2, $title)
    {
        return "<p><CENTER><H2 style='margin-top: 60px;'><font face='MS Sans Serif'>".$title."</font></H2><div style='text-align:center; display: flex; justify-content: center;'><img src='".$image1."' style='margin-right: 16px; display:inline-block;' width='285px' height='478.5px'><img src='".$image2."' style='display:inline-block;' width='285px' height='478.5px'></div><p style='margin-top: 60px;'><CENTER><h4><font face='MS Sans Serif'>Cards over £30 will be shipped using 48 hour tracked postage.</font></h4></CENTER></p><p><CENTER><h4><font face='MS Sans Serif'>Cert numbers may be different than the image for multiple quantity listings. Please message me to see an image of the exact card you will receive.</font></h4></CENTER></p>";
    }

    public function getPrice($searchPhrase)
    {
        return $this->ebayService->getEbayDataForPsaListing($searchPhrase)['lowest'];
    }

    public function getImages($cert)
    {
        $url = "https://api.psacard.com/publicapi/cert/GetImagesByCertNumber/" . $cert;
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'bearer ' . config('settings.psa_api_access_token'),
                    'Content-Type' => 'application/json',
                ],
            ]);

            $r = $response->getBody()->getContents();

            $result = json_decode($r);

            return $result;

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                echo $e->getResponse()->getBody()->getContents();
            } else {
                echo $e->getMessage();
            }
        }
    }
}
