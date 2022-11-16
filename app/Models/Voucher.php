<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'discount_value',
        'point_need',
    ];


    /**
     * User voucher relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user_vouchers(): HasMany
    {
        return $this->hasMany(UserVoucher::class);
    }
}
