<?php

// Ebay API Settings
return [
    // @todo - get all of these out of here and into .env
    'app_id' => env('ebay_app_id', null),
    'dev_id' => env('ebay_dev_id', null),
    'cert_id' => env('ebay_cert_id', null),

    'access_token_grant_url' => 'https://api.ebay.com/identity/v1/oauth2/token',
    'ruName' => 'le_southwart-lesouthw-SlabLi-nxeph',
    'test_sandbox_login' => env('sandbox_base', null) . urlencode(env('sandbox_test_login_scopes')),
    'prod_login'=> env('prod_login', null),
];
