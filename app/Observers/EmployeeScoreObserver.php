<?php

namespace App\Observers;

use App\Models\EmployeeScore;

class EmployeeScoreObserver
{
    public function creating(EmployeeScore $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
