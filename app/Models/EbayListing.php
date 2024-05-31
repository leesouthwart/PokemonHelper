<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EbayListing extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'price',
        'image_1',
        'image_2',
        'quantity',
        'batch_id',
        'search_phrase',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function scopeFilterDupes($query, $batch, $title)
    {
        return $query->where([
            'batch_id' => $batch,
            'title' => $title,
        ]);
    }
}
