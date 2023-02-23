<?php

namespace App\Observers;

use App\Models\CompanyRank;

class CompanyRankObserver
{
    public function creating(CompanyRank $model)
    {
        if (admin() && \admin()->type == 'admin') {

            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
