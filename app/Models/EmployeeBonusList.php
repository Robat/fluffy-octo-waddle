<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EmployeeBonusList extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('frequency', function (Builder $builder) {
            if (admin()) {
                if (isset(admin()->company->frequency()->id)) {
                    $builder->where('employee_bonus_lists.frequency_id', admin()->company->frequency()->id);
                } else {
                    $builder;
                }
            }
        });
    }

    // 綁定模擬ID
    public function bonus_setting()
    {
        return $this->belongsTo(CompanyBonusSetting::class, 'bonus_setting_id', 'id');
    }

    public function employee_bonuses()
    {
        return $this->hasMany(EmployeeBonusDetail::class,  'employee_bonus_id', 'id');
    }
}
