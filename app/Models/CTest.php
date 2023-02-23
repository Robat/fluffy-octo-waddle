<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CTest extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'sort', 'slug'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {

            if (admin()) {
                if (admin()->type == 'admin') {
                    $builder->where('c_tests.frequency_id', admin()->company->frequency()->id);
                }
                if (admin()->type == 'superadmin') {
                    $builder;
                }
            }
            if (employee()) {
                $builder->where('c_tests.id', employee()->id);
            }
        });
    }


    public function a_tests()
    {
        return $this->belongsToMany(ATest::class)
            ->where('a_test_c_test.frequency_id', '=', admin()->company->frequency()->id)
            ->using('App\Models\ATestCTest')->withPivot('frequency_id', 'score', 'c_sort', 'a_sort')->withTimestamps();
    }

    public static function showName($id)
    {
        return CTest::find($id)->name;
    }
}
