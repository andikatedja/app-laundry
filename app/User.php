<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get user transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'member_id');
    }

    public function vouchers()
    {
        return $this->hasMany(UserVoucher::class);
    }

    public function complaint_suggestions()
    {
        return $this->hasMany(ComplaintSuggestion::class);
    }
}
