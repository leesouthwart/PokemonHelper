<?php

namespace App\Services;

use App\Services\AccessTokenService;
use Illuminate\Support\Facades\Http;
use App\Models\Card;

class PsaService
{
    public function __construct()
    {
        $this->accessToken = (new AccessTokenService)->getAccessToken();
    }

    public function getPsaCardData($cert)
    {
//        $client = new \GuzzleHttp\Client();
//
//        $url = 'https://www.psacard.com/cert/' . $cert . '/psa';
//
//        $response = $client->request('GET', config('settings.psa_scrape_url_base') . $url, [
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

        $jsonData = [
            [
                "selector" => "#certImgFront img",
                "textNodes" => [
                    ""
                ],
                "htmlElements" => [
                    '<img src="https://d1htnxwo4o0jhw.cloudfront.net/cert/152762159/small/kXgCxEvrLUWkNecjUmI_gg.jpg" class="img-responsive">'
                ]
            ],
            [
                "selector" => "#certImgBack img",
                "textNodes" => [
                    ""
                ],
                "htmlElements" => [
                    '<img src="https://d1htnxwo4o0jhw.cloudfront.net/cert/152762159/small/wAm6iShv2kisGBexVy-l6Q.jpg" class="img-responsive">'
                ]
            ],
            [
                "selector" => ".table-header-right",
                "textNodes" => [
                    // Add your text nodes here if needed
                ],
                "htmlElements" => [
                    '<table class="table table-fixed table-header-right text-medium">
                <tbody>
                    <tr>
                        <th class="no-border">Certification Number</th>
                        <td class="no-border">86344925</td>
                    </tr>
                    <tr>
                        <th>Label Type</th>
                        <td>
                            <img data-src="https://i.psacard.com/psacard/images/cert/table-image-ink.png" width="69" height="38" class="lazy margin-right-min" alt="" />
                            <span class="inline-block padding-top-min">with fugitive ink technology</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Reverse Cert Number/Barcode</th>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <th>Year</th>
                        <td>2023</td>
                    </tr>
                    <tr>
                        <th>Brand</th>
                        <td>POKEMON JAPANESE SV3-RULER OF THE BLACK FLAME</td>
                    </tr>
                    <tr>
                        <th>Sport</th>
                        <td>TCG Cards</td>
                    </tr>
                    <tr>
                        <th>Card Number</th>
                        <td>066</td>
                    </tr>
                    <tr>
                        <th>Player</th>
                        <td>CHARIZARD ex</td>
                    </tr>
                    <tr>
                        <th>Variety/Pedigree</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Grade</th>
                        <td>GEM MT 10</td>
                    </tr>
                </tbody>
            </table>'
                ]
            ]
        ];

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
        dd($title);

        dd($jsonData);
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

        return [
            'brand' => $brand,
            'cardNumber' => $cardNumber,
            'pokemon' => $pokemon,
            'grade' => $grade,
        ];
    }

    public function formatTitle($mainData)
    {
        $title = [];
        $title['grade'] = config('psa.grade_labels')[$mainData['grade']];
        $title['pokemon'] = $this->stripTitle($mainData['pokemon']);

        // @todo reformat - instead of getting brand inside each function here, get it first then pass it to each function. Small performance save.
        // @todo reminder - fall back if brand doesnt exist
        $title['numbers'] = $this->setNumbers($mainData['brand'], $mainData['cardNumber']);
        $title['set'] = 'set name'; // @todo change this to Brand->friendly_label or something
        $title['description'] = $this->setDescription($mainData);

        return 'PSA ' . $title['grade'] . ' ' . $title['pokemon'] . ' ' . $title['numbers'] . ' ' . $title['set'] . ' ' . $title['description'];
    }

    public function stripTitle($pokemonName)
    {
        $lowercaseString = strtolower($pokemonName);
        $finalString = ucwords($lowercaseString);

        return $finalString;
    }

    public function setNumbers($brand, $cardNumber)
    {
        // @todo get Brand numbers here via Brand model.
        return $cardNumber . '/' . 'xxx';
    }

    public function setDescription($mainData)
    {
        // @todo future implementation - get ListingDescription where Grade = Data Grade. Where 'Japanese' = brand->is_japanese
        // @todo then do ListingDescription->label.
        return 'GEM MINT Pokemon Card';
    }
}
