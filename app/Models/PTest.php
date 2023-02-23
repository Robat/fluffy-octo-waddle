<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PTest extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'sort', 'slug'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {

            if (admin()) {
                if (admin()->type == 'admin') {
                    $builder->where('p_tests.frequency_id', admin()->company->frequency()->id);
                }
                if (admin()->type == 'superadmin') {
                    $builder;
                }
            }
            if (employee()) {
                $builder->where('p_tests.id', employee()->id);
            }
        });
    }

    public function a_tests()
    {
        return $this->belongsToMany(ATest::class)
            ->where('a_test_p_test.frequency_id', '=', admin()->company->frequency()->id)
            ->using('App\Models\ATestPTest')->withPivot('frequency_id', 'score', 'p_sort', 'a_sort');
    }
}
