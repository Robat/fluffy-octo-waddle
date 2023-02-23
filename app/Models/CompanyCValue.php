<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Lang;

class CompanyCValue extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {
            if (employee()) {
                $builder->where('company_c_values.company_id', employee()->company_id);
            }
        });
    }

    protected $table = "company_c_values";

    protected $fillable = ['id', 'name', 'frequency_id', 'bonus_calculation_id'];

    protected $guarded = ['id'];

    public function scopeCompany($query, $id)
    {
        return $query->where('company_c_values.company_id', '=', $id);
    }

    // public function currentID()
    // {
    //     return
    // }

    public function scopeManager($query, $id)
    {
        return null;
    }
}
