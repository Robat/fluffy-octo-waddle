<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyPerformance extends Model
{
    use HasFactory;



    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {
            if (admin()) {
                if (admin()->type == 'admin') {
                    $builder->where('company_performances.frequency_id', admin()->company->frequency()->id);
                }
                if (admin()->type == 'superadmin') {
                    $builder;
                }
            }
            if (employee()) {
                $builder->where('company_performances.company_id', employee()->company_id);
            }
        });
    }


    protected $table = "company_performances";

    // Don't forget to fill this array
    protected $fillable = ['title', 'company_id', 'performance_range', 'performance_month', 'status', 'default', 'sort'];

    protected $guarded = ['id'];


    public function scopeCompany($query, $id)
    {
        return $query->where('company_performances.company_id', '=', $id);
    }


    public function scopeManager($query, $id)
    {
        return null;
    }
}
