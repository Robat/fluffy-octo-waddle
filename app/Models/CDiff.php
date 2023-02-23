<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class CDiff extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'numbering', 'sort', 'slug', 'status'];

    protected static function boot()
    {
        parent::boot();


        static::addGlobalScope('company', function (Builder $builder) {

            if (admin()) {
                if (isset(admin()->company->frequency()->id)) {
                    $builder->where('c_diffs.frequency_id', admin()->company->frequency()->id);
                } else {
                    $builder;
                }
            }
        });
    }

    public function scopeCompany($query, $id)
    {
        return $query->where('company_frequencies.company_id', '=', $id);
    }

    public function a_diffs()
    {
        return $this->belongsToMany(ADiff::class)->using('App\Models\ADiffCDiff')->withPivot(['frequency_id']);
    }

    public function a_tests()
    {
        return $this->belongsToMany(ATest::class)
            ->where('a_test_c_diff.frequency_id', '=', admin()->company->frequency()->id)
            ->using('App\Models\ATestCDiff')->withPivot('frequency_id', 'score', 'c_sort', 'a_sort');
    }
}
