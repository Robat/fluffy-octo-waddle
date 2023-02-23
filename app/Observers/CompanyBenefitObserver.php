<?php

namespace App\Observers;

use App\Models\CompanyBenefit;

class CompanyBenefitObserver
{
    public function creating(CompanyBenefit $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $company = admin()->company;
            $model->company_id = admin()->company_id;
        }
    }
}
