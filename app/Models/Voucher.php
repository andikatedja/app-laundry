<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'discount_value',
        'point_need',
    ];

    public function user_vouchers()
    {
        return $this->hasMany(UserVoucher::class);
    }
}
