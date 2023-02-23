<?php

namespace App\Observers;

use App\Models\Admin;

class AdminUserObserver
{
    public function creating(Admin $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $company = admin()->company;
            $model->company_id = admin()->company_id;
        }
    }
}
