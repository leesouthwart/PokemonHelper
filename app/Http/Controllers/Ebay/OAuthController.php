<?php

namespace App\Http\Controllers\Ebay;

use App\Http\Controllers\Controller;
use App\Models\EbayProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Models\OauthToken;

class OAuthController extends Controller
{
    /**
     * @return redirect
     * Create an Oauth token and store against a user
     */
    public function create(Request $request)
    {

        // @todo - Tidy this up and make it look not retarded, thanks random polish guy though
        // @todo - Error handling nicer?

        $ch = curl_init(config('ebay.access_token_grant_url'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic '.base64_encode( config('ebay.app_id') . ':' . config('ebay.cert_id'))
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=authorization_code&code=". $request->get('code') ."&redirect_uri=".config('ebay.ruName'));

        $response = curl_exec($ch);

        $json = json_decode($response, true);

        $info = curl_getinfo($ch);
        curl_close($ch);

        if($json)
        {
            if(array_key_exists('access_token', $json)) {
                $oauth = OauthToken::create([
                    'token' => $json["access_token"],
                    'refresh_token' => $json["refresh_token"],
                    'user_id' => Auth::id(),
                ]);

                $ebayProfile = EbayProfile::where('user_id', auth()->user()->id)->first();

                if(!$ebayProfile) {
                    $ebayProfile = EbayProfile::create([
                        'user_id' => auth()->user()->id
                    ]);
                }
            }
        }

        return redirect('dashboard');
    }

}
