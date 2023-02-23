<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CenterPoint extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category', 'numbering', 'location', 'status', 'a_point', 'c_point', 'p_point'];

    protected static function boot()
    {
        parent::boot();


        static::addGlobalScope('company', function (Builder $builder) {

            if (admin()) {
                $builder->where('center_points.frequency_id', admin()->company->frequency()->id);
            }
        });
    }
}
