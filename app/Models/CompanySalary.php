<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Lang;

class CompanySalary extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {
            if (admin()) {
                if (admin()->type == 'admin') {
                    $builder->where('company_salaries.company_id', admin()->company_id);
                }
                if (admin()->type == 'superadmin') {
                    $builder;
                }
            }
            if (employee()) {
                $builder->where('company_salaries.company_id', employee()->company_id);
            }
        });
    }


    protected $table = "company_salaries";

    // Don't forget to fill this array
    protected $fillable = ['name', 'company_id', 'range', 'range_name', 'default'];

    protected $guarded = ['id'];


    public function scopeCompany($query, $id)
    {
        return $query->where('company_salaries.company_id', '=', $id);
    }


    public function scopeManager($query, $id)
    {
        return null;
    }
}
