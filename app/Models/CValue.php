<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CValue extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'range', 'values', 'description', 'point', 'mark', 'numbering', 'sort'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {

            if (admin()) {
                if (admin()->type == 'admin') {
                    $builder->where('c_values.frequency_id', admin()->company->frequency()->id);
                }
                if (admin()->type == 'superadmin') {
                    $builder;
                }
            }
            if (employee()) {
                $builder->where('c_values.id', employee()->id);
            }
        });
    }


    protected $table = "c_values";

    protected $guarded = ['id'];


    public function scopeCompany($query, $id)
    {
        return $query->where('c_values.id', '=', $id);
    }


    public function scopeManager($query, $id)
    {
        return null;
    }
}
