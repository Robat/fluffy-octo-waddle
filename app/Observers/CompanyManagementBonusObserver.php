<?php

namespace App\Observers;

use App\Models\CompanyManagementBonus;

class CompanyManagementBonusObserver
{
    public function creating(CompanyManagementBonus $model)
    {
        if (admin() && \admin()->type == 'admin') {

            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
