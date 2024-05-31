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

        $url = 'https://www.psacard.com/cert/' . $cert . '/psa';

        $response = $client->request('GET', config('settings.psa_scrape_url_base') . $url, [
            'headers' => [
                'accept' => 'application/json',
            ],
        ]);


        $json = json_decode($response->getBody()->getContents(), true);
        $jsonData = $json['result']['selectorElements'];

//        $jsonData = [
//            [
//                "selector" => "#certImgFront img",
//                "textNodes" => [
//                    ""
//                ],
//                "htmlElements" => [
//                    '<img src="https://d1htnxwo4o0jhw.cloudfront.net/cert/152762159/small/kXgCxEvrLUWkNecjUmI_gg.jpg" class="img-responsive">'
//                ]
//            ],
//            [
//                "selector" => "#certImgBack img",
//                "textNodes" => [
//                    ""
//                ],
//                "htmlElements" => [
//                    '<img src="https://d1htnxwo4o0jhw.cloudfront.net/cert/152762159/small/wAm6iShv2kisGBexVy-l6Q.jpg" class="img-responsive">'
//                ]
//            ],
//            [
//                "selector" => ".table-header-right",
//                "textNodes" => [
//                    // Add your text nodes here if needed
//                ],
//                "htmlElements" => [
//                    '<table class="table table-fixed table-header-right text-medium">
//                <tbody>
//                    <tr>
//                        <th class="no-border">Certification Number</th>
//                        <td class="no-border">86344925</td>
//                    </tr>
//                    <tr>
//                        <th>Label Type</th>
//                        <td>
//                            <img data-src="https://i.psacard.com/psacard/images/cert/table-image-ink.png" width="69" height="38" class="lazy margin-right-min" alt="" />
//                            <span class="inline-block padding-top-min">with fugitive ink technology</span>
//                        </td>
//                    </tr>
//                    <tr>
//                        <th>Reverse Cert Number/Barcode</th>
//                        <td>Yes</td>
//                    </tr>
//                    <tr>
//                        <th>Year</th>
//                        <td>2023</td>
//                    </tr>
//                    <tr>
//                        <th>Brand</th>
//                        <td>POKEMON JAPANESE SV3-RULER OF THE BLACK FLAME</td>
//                    </tr>
//                    <tr>
//                        <th>Sport</th>
//                        <td>TCG Cards</td>
//                    </tr>
//                    <tr>
//                        <th>Card Number</th>
//                        <td>066</td>
//                    </tr>
//                    <tr>
//                        <th>Player</th>
//                        <td>CHARIZARD ex</td>
//                    </tr>
//                    <tr>
//                        <th>Variety/Pedigree</th>
//                        <td></td>
//                    </tr>
//                    <tr>
//                        <th>Grade</th>
//                        <td>GEM MT 10</td>
//                    </tr>
//                </tbody>
//            </table>'
//                ]
//            ]
//        ];

        foreach($jsonData as $data) {
            // Image 1 Logic
            if($data['selector'] == '#certImgFront img') {
                $image1 = $this->formatImage1($data['htmlElements'][0]);
            }

            // Image 2 Logic
            if($data['selector'] == '#certImgBack img') {
                $image2 = $this->formatImage2($data['htmlElements'][0]);
            }

            if($data['selector'] == '.table-header-right') {
                $mainData = $this->formatMainData($data['htmlElements'][0]);
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
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($data);
        libxml_clear_errors();

        // Create a new DOMXPath instance
        $xpath = new \DOMXPath($dom);

        // Extract the values using XPath queries
        $brand = $xpath->query("//th[text()='Brand']/following-sibling::td")->item(0)->textContent ?? '';
        $cardNumber = $xpath->query("//th[text()='Card Number']/following-sibling::td")->item(0)->textContent ?? '';
        $pokemon = $xpath->query("//th[text()='Player']/following-sibling::td")->item(0)->textContent ?? '';
        $grade = $xpath->query("//th[text()='Grade']/following-sibling::td")->item(0)->textContent ?? '';
        $pedigree = $xpath->query("//th[text()='Variety/Pedigree']/following-sibling::td")->item(0)->textContent ?? '';

        return [
            'brand' => $brand,
            'cardNumber' => $cardNumber,
            'pokemon' => $pokemon,
            'grade' => $grade,
            'pedigree' => $pedigree
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
}
