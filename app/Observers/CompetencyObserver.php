<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\Competency;

class CompetencyObserver
{
    public function creating(Competency $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->company_id = admin()->company_id;
        }
    }
}
