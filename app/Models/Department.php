<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Lang;

class Department extends Model
{

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {
            if (admin()) {
                $builder->where('departments.company_id', admin()->company_id);
            }
        });
    }


    protected $table = 'departments';

    // Don't forget to fill this array
    protected $fillable = [];

    protected $guarded = ['id'];

    protected function DepartmentManager()
    {
        return $this->hasMany('App\Models\DepartmentManager', 'department_id', 'id');
    }

    public function members()
    {
        return $this->hasMany(Employee::class, 'department_id');
    }


    public function checkDepartment($manager_id)
    {
        $dept = DepartmentManager::select('department_id')->where('manager_id', $manager_id)->where('department_id', $this->id)->get();
        if (count($dept) > 0) return true;
        else
            return false;
    }

    public function scopeCompany($query, $id)
    {
        return $query->where('departments.company_id', '=', $id);
    }

    public function scopeManager($query, $id)
    {
        if (admin()->manager == 1) {
            return $query->join('department_managers', 'department_managers.department_id', '=', 'department.id')
                ->where('department_managers.manager_id', '=', $id);
        }
        return null;
    }
}
