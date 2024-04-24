<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    CONST GBP = 1;
    CONST JPY = 2;
    CONST AUD = 3;
    CONST USD = 4;

    public $fillable = [
        'name',
        'symbol',
        'code'
    ];

    /**
     * Get Currency Conversions for Currency
     * Usage: $price * $currency->convertTo->where('id', \App\Models\Currency::USD)->first()->pivot->conversion_rate}}
     */

    public function convertTo()
    {
        return $this->belongsToMany(Currency::class, 'currency_conversions', 'currency_id_1', 'currency_id_2')
            ->withPivot('conversion_rate');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Usage: $currency->conversionsTo->where('id', \App\Models\Currency::JPY)->first()->pivot->conversion_rate
     */
    public function convertFrom()
    {
        return $this->belongsToMany(Currency::class, 'currency_conversions', 'currency_id_2', 'currency_id_1')
            ->withPivot('conversion_rate');
    }

    public function scopeFilterTarget($query, $target)
    {
        return $query->where('currency_id_2', $target)->first()->pivot;
    }

}
