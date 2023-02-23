<?php

namespace App\Observers;

use App\Models\CompanyComplexBonusMethod;

class CompanyComplexBonusMethodObserver
{
    public function creating(CompanyComplexBonusMethod $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
