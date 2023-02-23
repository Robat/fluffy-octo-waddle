<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyRankDetail extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('frequency', function (Builder $builder) {
            if (admin()) {
                if (isset(admin()->company->frequency()->id)) {
                    $builder->where('company_rank_details.frequency_id', admin()->company->frequency()->id);
                } else {
                    $builder;
                }
            }
        });
    }

    protected $fillable = ['rank_id', 'frequency_id', 'grade_id', 'salary_max', 'salary_mid', 'salary_min'];

    protected $guarded = ['id'];


    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }
}
