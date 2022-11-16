<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id',
        'admin_id',
        'member_id',
        'discount',
        'total',
        'service_type_id',
        'service_cost',
        'payment_amount',
    ];

    /**
     * Member relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    /**
     * Admin relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Status relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Transaction details relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaction_details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Service type relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service_type(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    /**
     * Get formatted number of total
     *
     * @return string
     */
    public function getFormattedTotal(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    /**
     * Get formatted number of payment amount
     *
     * @return string
     */
    public function getFormattedPaymentAmount(): string
    {
        return 'Rp ' . number_format($this->payment_amount, 0, ',', '.');
    }

    /**
     * Get formatted number of service cost
     *
     * @return string
     */
    public function getFormattedServiceCost(): string
    {
        return 'Rp ' . number_format($this->service_cost, 0, ',', '.');
    }
}
