<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'icon',
    ];

    /**
     * Get the auctions for the category.
     */
    public function auctions(): HasMany
    {
        return $this->hasMany(Auction::class);
    }
}
