<?php

namespace App\Models;

use App\Http\Scopes\SellerScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends User
{
    use HasFactory, SoftDeletes;

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SellerScopes);
    }

    /**
     * Get all of the comments for the Seller
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
