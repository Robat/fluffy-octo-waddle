<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyBonusPersonal extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {

            if (admin()) {
                if (admin()->type == 'admin') {
                    $builder->where('company_bonus_personals.frequency_id', admin()->company->frequency()->id);
                }
                if (admin()->type == 'superadmin') {
                    $builder;
                }
            }
            if (employee()) {
                $builder->where('company_bonus_personals.id', employee()->id);
            }
        });
    }
}
