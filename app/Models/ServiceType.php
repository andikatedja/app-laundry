<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'cost'];

    /**
     * Transaction relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get formatted number of cost
     *
     * @return string
     */
    public function getFormattedCost(): string
    {
        return 'Rp ' . number_format($this->cost, 0, ',', '.');
    }
}
