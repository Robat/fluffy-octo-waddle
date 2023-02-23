<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Lang;

class CompanyCTest extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {

            if (admin()) {
                if (admin()->type == 'admin') {
                    $builder->where('company_c_tests.frequency_id', admin()->company->frequency()->id);
                }
                if (admin()->type == 'superadmin') {
                    $builder;
                }
            }
            if (employee()) {
                $builder->where('company_c_tests.company_id', employee()->company_id);
            }
        });
    }

    protected $table = "company_c_tests";

    protected $fillable = ['id', 'name', 'frequency_id', 'bonus_calculation_id', 'sort'];

    protected $guarded = ['id'];

    public function scopeCompany($query, $id)
    {
        return $query->where('company_c_tests.company_id', '=', $id);
    }

    public function company_atests()
    {
        return $this->belongsToMany(CompanyATest::class)->where('company_a_test_company_c_test.frequency_id', '=', admin()->company->frequency()->id)->withPivot('score', 'c_sort', 'a_sort')->withTimestamps();
    }
}
