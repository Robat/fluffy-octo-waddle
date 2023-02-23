<?php

namespace App\Observers;

use App\Models\ADiff;

class ADiffObserver
{
    public function creating(ADiff $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
}
