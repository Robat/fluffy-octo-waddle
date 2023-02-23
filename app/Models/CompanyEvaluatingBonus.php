<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyEvaluatingBonus extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'numbering',  'frequency_id', 'pay', 'total_numbering', 'sort', 'calculation_id'];
    protected static function boot()
    {
        parent::boot();


        static::addGlobalScope('company', function (Builder $builder) {

            if (admin()) {
                if (isset(admin()->company->frequency()->id)) {
                    $builder->where('company_evaluating_bonuses.frequency_id', admin()->company->frequency()->id);
                } else {
                    $builder;
                }
            }
        });
    }
}
