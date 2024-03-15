<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class EbayProfile extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get, or create, policies for returns, shipping and fufillment.
     */
    public function getOrCreatePolicies()
    {

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->user->oauth->token
        ])->get(config('ebay.endpoints.' . env('APP_ENV') . '.policies_fufill'));

        if($response->json[0]['longMessage'] == 'The seller profile ID is not valid') {
            return [
                'success' => false,
                'message' => 'Invalid Profile'
            ];
        }

        return [
            'success' => true,
        ];
    }
}
