<?php

namespace App\Observers;

use App\Models\CenterPoint;

class CenterPointObserver
{
    public function creating(CenterPoint $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
