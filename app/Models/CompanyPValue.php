<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Lang;

class CompanyPValue extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {
            if (admin()) {
                if (admin()->type == 'admin') {
                    $builder->where('company_p_values.frequency_id', admin()->company->frequency()->id);
                }
                if (admin()->type == 'superadmin') {
                    $builder;
                }
            }
            if (employee()) {
                $builder->where('company_p_values.company_id', employee()->company_id);
            }
        });
    }


    protected $table = "company_p_values";

    protected $fillable = ['id', 'name', 'frequency_id', 'bonus_calculation_id', 'sort'];

    protected $guarded = ['id'];


    public function scopeCompany($query, $id)
    {
        return $query->where('company_p_values.company_id', '=', $id);
    }

    public function company_dtests()
    {
        return $this->belongsToMany(CompanyDTest::class)->where('company_d_test_company_p_test.frequency_id', '=', admin()->company->frequency()->id)->withPivot('score', 'p_sort', 'd_sort')->withTimestamps();
    }

    public function scopeManager($query, $id)
    {
        return null;
    }
}
