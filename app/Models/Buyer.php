<?php

namespace App\Models;

use App\Http\Scopes\BuyerScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buyer extends User
{
    use HasFactory, SoftDeletes;

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BuyerScopes);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
