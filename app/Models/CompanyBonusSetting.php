<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyBonusSetting extends Model
{
    use HasFactory;
    protected $fillable = ['bonus_frequency'];
    protected $guarded = [];
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {
            if (admin()) {
                if (isset(admin()->company->frequency()->id)) {
                    $builder->where('company_bonus_settings.frequency_id', admin()->company->frequency()->id);
                } else {
                    $builder;
                }
            }
        });
    }


    public function rank()
    {
        return $this->hasOne(CompanyRank::class,  'id', 'salaryScale_id');
    }


    public static function bonusId()
    {
        return CompanyBonusSetting::where('status', '1')->first()->id;
    }

    public function performance()
    {
        return $this->hasOne(CompanyPerformanceBonusList::class, 'id', 'bonus_setting_id');
    }
}
