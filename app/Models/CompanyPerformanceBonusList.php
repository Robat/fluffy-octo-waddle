<?php

namespace App\Models;

use App\Models\CompanyPerformanceBonus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyPerformanceBonusList extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'status', 'bonus_setting_id', 'frequency_id'];
    protected $guarded = [];
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('frequency', function (Builder $builder) {
            if (admin()) {
                if (isset(admin()->company->frequency()->id)) {
                    $builder->where('company_performance_bonus_lists.frequency_id', admin()->company->frequency()->id);
                } else {
                    $builder;
                }
            }
        });
    }

    public function bonus_setting()
    {
        return $this->belongsTo(CompanyBonusSetting::class, 'bonus_setting_id', 'id');
    }

    public function performances()
    {
        return $this->hasMany(CompanyPerformanceBonus::class,  'performance_id', 'id');
    }
}
