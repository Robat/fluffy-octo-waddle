<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CompanyFrequency extends Model
{
    use HasFactory;

    // Don't forget to fill this array
    protected $fillable = ['frequency_name', 'year_name', 'remark', 'start_at', 'ends_at', 'status'];

    protected $guarded = ['id'];


    protected static function boot()
    {
        parent::boot();


        static::addGlobalScope('company', function (Builder $builder) {

            if (admin()) {
                $builder->where('company_frequencies.company_id', admin()->company_id);
            }
        });
    }

    public function scopeCompany($query, $id)
    {
        return $query->where('company_frequencies.company_id', '=', $id);
    }
}
