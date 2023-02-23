<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Grade extends Model
{
    use HasFactory;

    protected $table = 'grades';

    // Don't forget to fill this array
    protected $fillable = ['grade', 'competencyID', 'salary_max', 'salary_mid', 'salary_min', 'designation'];

    protected $guarded = ['id'];

    public function competency()
    {
        return $this->belongsTo(Competency::class);
    }


    public function employees()
    {
        return $this->hasManyThrough(Employee::class, Designation::class);
    }


    public function designations()
    {
        return $this->hasMany(Designation::class);
    }



    public function scopeCompany($query, $id)
    {
        return $query->where('grades.company_id', '=', $id);
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
