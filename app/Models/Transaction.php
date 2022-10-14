<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function transaction_details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function getFormattedTotal(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public function getFormattedPaymentAmount(): string
    {
        return 'Rp ' . number_format($this->payment_amount, 0, ',', '.');
    }

    public function getFormattedServiceCost(): string
    {
        return 'Rp ' . number_format($this->service_cost, 0, ',', '.');
    }
}
