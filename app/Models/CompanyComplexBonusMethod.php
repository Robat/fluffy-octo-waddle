<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CompanyComplexBonusMethod extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {
            if (admin()) {
                if (isset(admin()->company->frequency()->id)) {
                    $builder->where('company_complex_bonus_methods.frequency_id', admin()->company->frequency()->id);
                } else {
                    $builder;
                }
            }
        });
    }

    protected $table = "company_complex_bonus_methods";

    // Don't forget to fill this array
    protected $fillable = ['name', 'frequency_id'];

    protected $guarded = ['id'];

    public function scopeFrequency($query, $id)
    {
        return $query->where('company_complex_bonus_methods.frequency_id', '=', $id);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function details()
    {
        return $this->hasMany(CompanyComplexBonusMethodDetail::class, 'complex_id', 'id');
    }
}
