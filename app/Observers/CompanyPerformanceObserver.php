<?php

namespace App\Observers;

use App\Models\CompanyPerformance;


class CompanyPerformanceObserver
{

    public function creating(CompanyPerformance $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
