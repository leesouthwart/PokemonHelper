<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Ebay\OAuthController as EbayOAuthController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\CardController;

use App\Models\EbayProfile;
use App\Models\OauthToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/approved', [EbayOAuthController::class, 'create'])->name('oauth_creation');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//
//Route::get('test', function() {
//    $response = Http::withHeaders([
//        'X-EBAY-C-MARKETPLACE-ID' => 'EBAY_GB',
//        'Authorization' => 'Bearer ' . 'v^1.1#i^1#p^1#I^3#f^0#r^0#t^H4sIAAAAAAAAAOVYe2wURRjv9WWQFqg2UHzlshqj4O7N7t3edTfc4bWlUil93VGwQGBud7a3dG933dnr9QzVWh4hmhgTBROMQhAjKCn8AQqJNBjio0TFGOAPImhAhZigiULwQdTZaynXSnj1jE28fzYz8803v+/3veYG9BRPmLF27tqLpa7b8jf3gJ58l4udCCYUF82cVJB/V1EeyBJwbe55oKewt+DsLAwTmim2IGwaOkburoSmYzEzGaSSli4aEKtY1GECYdGWxEh4fr3IMUA0LcM2JEOj3HU1QUrhuIDCK5IgSayAFEhm9cs6o0aQ8sX8IMADXuBYn+L3KWQd4ySq07ENdTtIcYDz0YCjWT7KcaIXiKyf8Qp8G+VuRRZWDZ2IMIAKZeCKmb1WFtZrQ4UYI8smSqhQXbg20hiuq5nTEJ3lydIVGuIhYkM7iUeOqg0ZuVuhlkTXPgZnpMVIUpIQxpQnNHjCSKVi+DKYW4CfoVpm/RAiPqAALgAB68sJlbWGlYD2tXE4M6pMKxlREem2aqevxyhhI7YCSfbQqIGoqKtxO5/mJNRURUVWkJpTFX4i3NREhTSEjaQdT9ERDcbqVWzTkapFdIxDPh74AU8rJLT8AqsMHTSobYjmUSdVG7qsOqRhd4NhVyGCGo3mhsvihgg16o1WWLEdRMNy/igAwxyybY5TB71IYOqOX1GCEOHODK/vgeHdtm2psaSNhjWMXshQFKSgaaoyNXoxE4tD4dOFg1Tctk3R40mlUkzKyxhWu4cDgPUsml8fkeIoQZKxK+Hk+qC8ev0NtJoxRUJkJ1ZFO20SLF0kVgkAvZ0KeQWBE9gh3kfCCo2e/cdEls2ekRmRqwyp5L1yJYcqBVQp8TCQk2ITGgpSj4MDxWCaTkCrA9mmBiVESyTOkglkqbLo5RXOW6kgWvYLCu0TFIWO8bKfZhWEAEKxmCRU/p8S5UZDPYIkC9k5ifWcxfm8J5s7W8Kp9oW1Mb6jPlLDxzvl+qbICo6N18bhvJrOx+OpMG6SHnuqOXij2XBV46s1lTATJefnggAn13NHwlwD20gek3kRyTBRk6GpUnp8OdhryU3QstNVyTQZR5Cmkc+YTA2bZl1uKnbOjLzJYnFrdueuU/1HXeqqVmEncMeXVc5+TBRAU2VIH3JyPc1IRsJjQOJkZ3pZBrV7lOBVhTyxZJpRSV1mSHeSb3xLVu9jCF61PW7jMRGtkgvzuKKZWD5IgSoP3nSZDA8M7pQYy0koi1zymUbn4hc1OpBO2qhtGZqGrNaxhZxTQBKJpA1jGhpvlSQHGaXCcdbj2QCoBF4/x/rHZJeU6eDL/tUaWPhs/zgo/zdxnfeMfFwI5WV+bK9rP+h17ct3uUAA0OxM8HBxwYLCghIKk1LEYKjLMaOLUaHCkMqik//OFmI6UNqEqpVf7FKPH5F+zXrW2LwUVAw/bEwoYCdmvXKAe66sFLGTp5VyPsCxPMd5AetvA/dfWS1kpxaWf7Om/WkOP7i8esrJnUv33XfqpdnnXgelw0IuV1FeYa8rb3XN9+fD25pDu55f0LCQ3v/2wOTt5fQfJcdW/TL34sozA2df/NCC5bt9ANx+KfrOxrKNxqEU+LPs6OKBspX87+WvHPxyRn//1h8/Y1ZMn3T3ma/uXbeBS+y986O2HezHW0peNoXTR+VI/a471mzsTlS86U6+63/VOntMfW3B+klrvi3dvrs8hem+05Pfaun2nPypN1pqHpq+uuTCkuSFwwe4OUJF9w+pR7ftmPbp7HXdR2bBh5pavmj42vfXQKC4nO4/deATcH7LiV2HV7VcnLhBOrio7VLRc3vX/7azMJUnbD36iG/5nr6pze+/0dk3hQv6Pu9b8sEL+ntl5/J+XryptfbE8U3PfFdB74EFg278GyUydUlwEgAA'
//    ])->get('https://api.sandbox.ebay.com/buy/browse/v1/item_summary/search?q=car&limit=3');
//
//    dd($response->json());
//    return $response->json();
//});

Route::get('test2', function() {
    $client = new \GuzzleHttp\Client();

    $response = $client->request('GET', config('settings.scrape_url_base') . 'https://www.cardrush-pokemon.jp/product/2628', [
        'headers' => [
            'accept' => 'application/json',
        ],
    ]);


    $json = json_decode($response->getBody()->getContents(), true);
    dd($json['result']['selectorElements']);
});

Route::get('test3', function() {
   $ebay = new \App\Services\EbayService();
   $items = $ebay->getEbayData('mew 183 172');
});

Route::middleware('currency.convert')->group(function () {
    Route::get('cardrush', [CardController::class, 'index'])->name('cardrush');
    Route::post('store_card', [CardController::class, 'store'])->name('card.store');
});




require __DIR__.'/auth.php';
