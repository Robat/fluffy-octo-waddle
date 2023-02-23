<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\Designation;

class DesignationObserver
{
    public function creating(Designation $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->company_id = admin()->company_id;
        }
    }
}
