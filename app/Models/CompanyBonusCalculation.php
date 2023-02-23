<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyBonusCalculation extends Model
{
    use HasFactory;

    protected $fillable = ['bonus_setting_id', 'frequency_id'];
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('frequency', function (Builder $builder) {
            if (admin() && isset(admin()->company->frequency()->id)) {
                $builder->where('frequency_id', admin()->company->frequency()->id);
            }
        });
    }

    public function bonus_setting()
    {
        return $this->belongsTo(CompanyBonusSetting::class, 'bonus_setting_id', 'id');
    }

    public function aTests()
    {
        return $this->hasMany(CompanyATest::class, 'bonus_calculation_id', 'id');
    }

    public function dTests()
    {
        return $this->hasMany(CompanyDTest::class, 'bonus_calculation_id', 'id');
    }

    public function performanceBonuses()
    {
        return $this->hasMany(CompanyPerformanceBonus::class, 'calculation_id', 'id');
    }

    public function evaluatingBonuses()
    {
        return $this->hasMany(CompanyEvaluatingBonus::class, 'calculation_id', 'id');
    }
}
