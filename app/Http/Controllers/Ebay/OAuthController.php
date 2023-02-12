<?php

namespace App\Http\Controllers\Ebay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\OauthToken;

class OAuthController extends Controller
{
    /**
     * @return redirect
     * Create an Oauth token and store against a user
     */
    public function create(Request $request)
    {
        $token = $request->get('code');

        $oauth = OauthToken::create([
            'token' => $token,
            'user_id' => Auth::id()
        ]);

        $message = $oauth ? 'Sucessfully linked Ebay account' : 'An error occurred, please try again later.';

        return redirect('dashboard')->with(['message', $message]);
    }

}
