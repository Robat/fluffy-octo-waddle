<?php

namespace App\Observers;

use App\Models\Employee;

use App\Models\CompanyBonusList;
use App\Models\CompanyBonusSetting;
use App\Models\EmployeeScore;

class EmployeeObserver
{
    public function creating(Employee $employee)
    {
        if (admin()) {
            $company = admin()->company;
            $employee->company_id = admin()->company_id;
        }
    }

    public function created(Employee $employee)
    {
        $bonus_id = CompanyBonusSetting::bonusId();

        CompanyBonusList::UpdateOrCreate([
            'employee_id' => $employee->id,
            'bonus_id' => $bonus_id,
        ]);

        $employee_salary = array_sum($employee->employee_salaries->pluck('salary')->toArray());
    }

    // public function updated(Employee $employee)
    // {
    //     $employee_salary = array_sum($employee->employee_salaries->pluck('salary')->toArray());
    //     EmployeeScore::find

    // }
}
