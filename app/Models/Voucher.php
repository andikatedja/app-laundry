<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function user_vouchers()
    {
        return $this->hasMany(UserVoucher::class);
    }
}
