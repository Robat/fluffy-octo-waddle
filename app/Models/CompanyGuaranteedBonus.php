<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyGuaranteedBonus extends Model
{
    use HasFactory;


    protected $fillable = ['id', 'name', 'status', 'numbering', 'days_to_zero', 'is_incumbent_ratio', 'fixed_amount', 'fixed_numbering'];
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {
            if (admin()) {
                if (isset(admin()->company->frequency()->id)) {
                    $builder->where('company_guaranteed_bonuses.frequency_id', admin()->company->frequency()->id);
                } else {
                    $builder;
                }
            }
        });
    }
}
