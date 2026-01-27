<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'is_active',
    ];

    /**
     * Get the auctions for the category.
     */
    public function auctions(): HasMany
    {
        return $this->hasMany(Auction::class);
    }
}
