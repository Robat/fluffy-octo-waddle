<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyManagementBonus extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {
            if (admin()) {
                if (isset(admin()->company->frequency()->id)) {
                    $builder->where('company_management_bonuses.frequency_id', admin()->company->frequency()->id);
                } else {
                    $builder;
                }
            }
        });
    }


    // Don't forget to fill this array
    protected $fillable = ['name', 'frequency_id', 'competency', 'number', 'bonus'];

    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany(CompanyManagementBonusDetail::class, 'management_id', 'id');
    }
}
