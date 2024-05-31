<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class AccessTokenService
{
    public function getAccessToken()
    {
        $accessToken = Cache::get('access_token');

        if (!$accessToken) {
            $accessToken = $this->fetchNewAccessToken();
            Cache::put('access_token', $accessToken, 1440); // 1440 minutes = 24 hours
        }

        return $accessToken;
    }

    private function fetchNewAccessToken()
    {
        $url = env('APP_ENV') == 'local' ? 'https://api.sandbox.ebay.com/identity/v1/oauth2/token' : 'https://api.ebay.com/identity/v1/oauth2/token';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic '.base64_encode( config('ebay.app_id') . ':' . config('ebay.cert_id'))
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials&scope=". urlencode("https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/buy.item.feed https://api.ebay.com/oauth/api_scope/buy.marketplace.insights"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials&scope=". urlencode("https://api.ebay.com/oauth/api_scope"));

        $response = curl_exec($ch);

        $json = json_decode($response, true);

        $info = curl_getinfo($ch);
        curl_close($ch);

        if($json && isset($json['access_token'])) {
            return $json['access_token'];
        } else {
            throw new \Exception('Failed to fetch access token from eBay: ' . $response);
        }
    }
}
