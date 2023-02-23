<?php

namespace App\Models;


use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Traits\HasSubscriptions;

use Illuminate\Notifications\Notifiable;
use App\Models\Traits\HasConfirmationTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasConfirmationTokens, Billable, HasSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'activated'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasActivated()
    {
        return $this->activated;
    }

    public function hasNotActivated()
    {
        return !$this->hasActivated();
    }

    public function team()
    {
        return $this->hasOne(Team::class);
    }

    public function plan()
    {
        return $this->plans->first();
    }

    public function getPlanAttribute()
    {
        return $this->plan();
    }

    public function plans()
    {
        return $this->hasManyThrough(
            Plan::class,
            Subscription::class,
            'user_id',
            'gateway_id',
            'id',
            'stripe_plan'
        )->orderBy('subscriptions.created_at', 'desc');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }
}
