<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;
    protected $table =  'plans';
    protected $dates = ['created_at'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function isForTeams()
    {
        return $this->teams_enabled === true;
    }

    public function isNotForTeams()
    {
        return !$this->isForTeams();
    }

    public function scopeActive(Builder $builder)
    {
        return $builder->where('active', true);
    }

    public function scopeExcept(Builder $builder, $planId)
    {
        return $builder->where('id', '!=', $planId);
    }

    public function scopeForUsers(Builder $builder)
    {
        return $builder->where('teams_enabled', false);
    }

    public function scopeForTeams(Builder $builder)
    {
        return $builder->where('teams_enabled', true);
    }
}
