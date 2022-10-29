<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'price_list_id',
        'quantity',
        'price',
        'sub_total'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function price_list()
    {
        return $this->belongsTo(PriceList::class);
    }

    public function getFormattedPrice(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedSubTotal(): string
    {
        return 'Rp ' . number_format($this->sub_total, 0, ',', '.');
    }
}
