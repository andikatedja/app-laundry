<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'cost'];

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getFormattedCost(): string
    {
        return 'Rp ' . number_format($this->cost, 0, ',', '.');
    }
}
