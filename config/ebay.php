<?php

// Ebay API Settings
return [
    'app_id' => env('APP_ENV') == 'local' ? env('local_ebay_app_id') : env('ebay_app_id', null),
    'dev_id' => env('APP_ENV') == 'local' ? env('local_ebay_dev_id') : env('ebay_dev_id'),
    'cert_id' => env('APP_ENV') == 'local' ? env('local_ebay_cert_id') : env('ebay_cert_id'),

    'access_token_grant_url' =>  env('APP_ENV') == 'local' ? 'https://api.sandbox.ebay.com/identity/v1/oauth2/token' : 'https://api.ebay.com/identity/v1/oauth2/token',
    'ruName' => env('APP_ENV') == 'local' ? 'le_southwart-lesouthw-SlabLi-nsergyhf' : 'le_southwart-lesouthw-SlabLi-nxeph',
    'login'=> env('APP_ENV') == 'local' ? env('local_login', null) : env('prod_login', null),
    'endpoints' => [
        'local' => [
            'identity' => 'https://apiz.sandbox.ebay.com/commerce/identity/v1/user/',
            'policies_fufill' => 'https://api.sandbox.ebay.com/sell/account/v1/fulfillment_policy?marketplace_id=EBAY_US'
        ],
        'production' => [
            'identity' => 'https://apiz.ebay.com/commerce/identity/v1/user/'
        ]
    ]
];
