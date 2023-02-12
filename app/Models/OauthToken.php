<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OauthToken extends Model
{
    use HasFactory;

    public $fillable = [
        'token',
        'user_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * User Relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
