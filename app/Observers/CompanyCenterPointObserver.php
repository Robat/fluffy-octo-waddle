<?php

namespace App\Observers;

use App\Models\CompanyCenterPoint;

class CompanyCenterPointObserver
{
    public function creating(CompanyCenterPoint $model)
    {
        if (admin() && \admin()->type == 'admin') {


            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
