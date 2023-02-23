<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\Grade;

class GradeObserver
{
    public function creating(Grade $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->company_id = admin()->company_id;
        }
    }
}
