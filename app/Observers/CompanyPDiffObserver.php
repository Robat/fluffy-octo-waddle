<?php

namespace App\Observers;

use App\Models\CompanyCValue;
use App\Models\CompanyCTest;
use App\Models\CompanyPDiff;

class CompanyPDiffObserver
{
    public function creating(CompanyPDiff $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
