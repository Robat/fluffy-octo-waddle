<?php

namespace App\Observers;

use App\Models\CompanyRankDetail;

class CompanyRankDetailObserver
{
    public function creating(CompanyRankDetail $model)
    {
        if (admin() && \admin()->type == 'admin') {

            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
