<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\Department;

class DepartmentObserver
{
    public function creating(Department $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->company_id = admin()->company_id;
        }
    }
}
