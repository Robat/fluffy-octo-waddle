<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyBonusListDetail extends Model
{
    use HasFactory;


    protected $fillable = ['bonus_id', 'employee_id', 'bonus_list_id', 'frequency_id', 'salary'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {

            if (admin()) {
                if (admin()->type == 'admin') {
                    $builder->where('company_bonus_list_details.frequency_id', admin()->company->frequency()->id);
                }
                if (admin()->type == 'superadmin') {
                    $builder;
                }
            }
            if (employee()) {
                $builder->where('company_bonus_list_details.id', employee()->id);
            }
        });
    }

    public function frequency()
    {
        return $this->belongsTo(Frequency::class, 'frequency_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function company_bonus()
    {
        return $this->belongsTo(CompanyBonusSetting::class, 'bonus_id', 'id');
    }
}
