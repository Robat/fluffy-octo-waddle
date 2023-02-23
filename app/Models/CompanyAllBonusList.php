<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class CompanyAllBonusList extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('frequency', function (Builder $builder) {
            if (admin()) {
                if (isset(admin()->company->frequency()->id)) {
                    $builder->where('company_all_bonus_lists.frequency_id', admin()->company->frequency()->id);
                } else {
                    $builder;
                }
            }
        });
    }

    // Don't forget to fill this array
    protected $fillable = ['name', 'frequency_id', 'bonus_setting_id', 'status'];

    // 綁定模擬ID
    public function bonus_setting()
    {
        return $this->belongsTo(CompanyBonusSetting::class, 'bonus_setting_id', 'id');
    }

    // 多筆獎金
    public function bonusListDetails()
    {
        return $this->hasMany(CompanyBonusList::class,  'bonus_list_id', 'id');
    }
}
