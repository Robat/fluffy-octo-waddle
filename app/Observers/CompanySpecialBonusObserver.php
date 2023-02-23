<?php

namespace App\Observers;

use App\Models\CompanySpecialBonus;

class CompanySpecialBonusObserver
{
    public function creating(CompanySpecialBonus $model)
    {
        if (admin() && \admin()->type == 'admin') {

            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
