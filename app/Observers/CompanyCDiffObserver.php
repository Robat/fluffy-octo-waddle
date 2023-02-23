<?php

namespace App\Observers;

use App\Models\CompanyCValue;
use App\Models\CompanyCTest;
use App\Models\CompanyCDiff;

class CompanyCDiffObserver
{
    public function creating(CompanyCDiff $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
