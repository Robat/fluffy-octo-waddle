<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\CompanyAllBonusList;
use App\Models\CompanyBonusListDetail;

class CompanyAllBonusListObserver
{
    public function creating(CompanyAllBonusList $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }

    public function created(CompanyAllBonusList $model)
    {
        // $bonus_list_detail = CompanyBonusListDetail::firstOrNew([
        //     'bonus_setting_id' => $model->id,
        // ]);


        // $bonus_list_detail->save();
        $employees = Employee::with('employee_salaries')->select('employees.id')->get();
        // dd($employees->toArray());
        foreach ($employees as $employee) {
            CompanyBonusListDetail::updateOrCreate([
                'bonus_id' => $model->bonus_setting_id,
                'bonus_list_id' => $model->id,
                'employee_id' => $employee->id,
                'salary' => array_sum($employee->employee_salaries->pluck('salary')->toArray())
            ]);
        }
    }
}
