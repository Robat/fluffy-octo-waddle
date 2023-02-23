<?php

namespace App\Observers;

use App\Models\Salary;
use App\Models\Employee;
use App\Models\EmployeeSalary;

class SalaryObserver
{
    public function creating(Salary $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->company_id = admin()->company_id;
        }
    }

    public function created(Salary $model)
    {
        $company_salaries = $model->id;
        // dd($company_salaries);
        $company_employees = Employee::select('id', 'employeeID')->pluck('employeeID', 'id')->toArray();
        foreach ($company_employees as $key => $value) {
            EmployeeSalary::UpdateOrCreate(
                [
                    'employee_id' => $key,
                    'salary_id' => $model->id
                ]
            );
        }
    }
}
