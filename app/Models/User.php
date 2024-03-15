<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\Http;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * Oauth Token relationship
     */
    public function oauth()
    {
        return $this->hasOne(OauthToken::class)->latest();
    }

    public function ebay()
    {
        return $this->hasOne(EbayProfile::class);
    }

    public function ebayAuthCheck()
    {
        if(is_null($this->oauth)) {
            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->oauth->token
        ])->get(config('ebay.endpoints.' . env('APP_ENV') . '.identity'));

        if($response->json('username')) {
            return true;
        }

        return false;
    }

    public function getName()
    {

        // @todo Add some sort of way of checking if we are in local everywhere cuz this is a madness
        if($this->oauth && $this->oauth->token) {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->oauth->token
            ])->get(config('ebay.endpoints.' . env('APP_ENV') . '.identity'));
            return $response->json('username');
        }
    }
}
