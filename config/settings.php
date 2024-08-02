<?php

return [
    'currency_conversion_api_url_base' => 'https://v6.exchangerate-api.com/v6/'. env('currency_conversion_api_key') .'/latest/',
    'scrape_url_base' => 'https://api.scrapingrobot.com/?token=' . env('scraping_robot_api_key') . '&scrapeSelector=%23main_img_1&scrapeSelector=%23pricech&url=',
    'psa_scrape_url_base' => 'https://api.scrapingrobot.com/?token=' . env('scraping_robot_api_key') . '&render=true&scrapeSelector=%23certImgFront%20img&scrapeSelector=%23certImgBack%20img&scrapeSelector=%2Etable-header-right&url=',
    //'psa_scrape_url_base' => 'https://api.scrapingrobot.com/?token=' . env('scraping_robot_api_key') . '&render=true&url=',
    'psa_api_access_token' => env('psa_api_access_token'),
];
