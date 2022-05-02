<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user_vouchers()
    {
        return $this->hasMany(UserVoucher::class);
    }
}
