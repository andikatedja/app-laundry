<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Price list relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function price_lists(): HasMany
    {
        return $this->hasMany(PriceList::class);
    }
}
