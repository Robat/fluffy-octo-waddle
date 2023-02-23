<?php

namespace App\Observers;

use App\Models\CompanyADiff;

class CompanyADiffObserver
{
    public function creating(CompanyADiff $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
