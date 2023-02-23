<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\CompanyBonusSetting;
use App\Models\CompanyFrequency;

class CompanyFrequencyObserver
{
    public function creating(CompanyFrequency $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->company_id = admin()->company_id;
        }
    }

    public function created(CompanyFrequency $model)
    {

        if (count($model->toArray()) > 0) {
            $company_bonus_setting = new CompanyBonusSetting;
            $company_bonus_setting->frequency_id = $model->id;
            $company_bonus_setting->save();
        }
    }
}
