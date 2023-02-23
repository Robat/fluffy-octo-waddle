<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'remarks', 'status'];
    protected $table = 'salaries';
    protected $guarded = ['id'];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {

            $query->remarks = $query->remarks ?? trim($query->type);
        });
    }

    // public function scopeCompany($query, $id)
    // {
    //     return $query->join('employees', 'salary.employee_id', '=', 'employees.employeeID')
    //         ->where('employees.company_id', '=', $id);
    // }

    public function scopeCompany($query, $id)
    {
        return $query->where('salaries.company_id', '=', $id);
    }
}
