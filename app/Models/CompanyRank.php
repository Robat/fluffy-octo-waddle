<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Lang;

class CompanyRank extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {
            if (admin()) {
                if (isset(admin()->company->frequency()->id)) {
                    $builder->where('company_ranks.frequency_id', admin()->company->frequency()->id);
                } else {
                    $builder;
                }
            }
        });
    }


    protected $table = "company_ranks";

    // Don't forget to fill this array
    protected $fillable = ['name', 'frequency_id'];

    protected $guarded = ['id'];


    public function scopeFrequency($query, $id)
    {
        return $query->where('company_ranks.frequency_id', '=', $id);
    }


    public function scopeManager($query, $id)
    {
        return null;
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function details()
    {
        return $this->hasMany(CompanyRankDetail::class, 'rank_id');
    }

    public function bonusSetting()
    {
        return $this->belongsTo(CompanyBonusSetting::class, 'salaryScale_id');
    }
}
