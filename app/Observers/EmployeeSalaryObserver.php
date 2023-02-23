<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\EmployeeSalary;

class EmployeeSalaryObserver
{
    public function creating(EmployeeSalary $model)
    {
        if (admin() && \admin()->type == 'admin') {


            $model->frequency_id = admin()->company->frequency()->id;
        }
    }

    public function created(EmployeeSalary $model)
    {
    }

    public function updated(EmployeeSalary $model)
    {
    }
}
