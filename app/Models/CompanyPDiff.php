<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CompanyPDiff extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {

            if (admin()) {
                if (admin()->type == 'admin') {
                    $builder->where('company_p_diffs.frequency_id', admin()->company->frequency()->id);
                }
                if (admin()->type == 'superadmin') {
                    $builder;
                }
            }
            if (employee()) {
                $builder->where('company_p_diffs.company_id', employee()->company_id);
            }
        });
    }


    protected $table = "company_p_diffs";

    protected $fillable = ['id', 'name', 'frequency_id', 'numbering', 'bonus_calculation_id'];

    protected $guarded = ['id'];

    public function scopeCompany($query, $id)
    {
        return $query->where('company_p_diffs.company_id', '=', $id);
    }


    public function scopeManager($query, $id)
    {
        return null;
    }
}
