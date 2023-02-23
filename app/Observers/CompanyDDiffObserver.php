<?php

namespace App\Observers;

use App\Models\CompanyDDiff;

class CompanyDDiffObserver
{
    public function creating(CompanyDDiff $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
