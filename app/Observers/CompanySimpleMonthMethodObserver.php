<?php

namespace App\Observers;

use App\Models\CompanySimpleMonthMethod;

class CompanySimpleMonthMethodObserver
{
    public function creating(CompanySimpleMonthMethod $model)
    {
        if (admin() && \admin()->type == 'admin') {

            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
