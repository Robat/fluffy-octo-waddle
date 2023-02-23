<?php

namespace App\Observers;

use App\Models\CompanyBusinessProfit;

class CompanyBusinessProfitObserver
{
    public function creating(CompanyBusinessProfit $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $company = admin()->company;
            $model->company_id = admin()->company_id;
        }
    }
}
