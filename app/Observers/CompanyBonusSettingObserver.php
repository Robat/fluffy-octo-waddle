<?php

namespace App\Observers;

use App\Models\CompanyAllBonusList;
use App\Models\CompanyBonusSetting;
use App\Models\CompanyBonusCalculation;
use App\Models\CompanyEvaluatingBonusList;
use App\Models\CompanyPerformanceBonusList;

class CompanyBonusSettingObserver
{
    public function creating(CompanyBonusSetting $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }

    public function created(CompanyBonusSetting $model)
    {
        $bonus_calculation = CompanyBonusCalculation::firstOrNew([
            'bonus_setting_id' => $model->id,
        ]);
        $bonus_calculation->save();

        $all_bonus = CompanyAllBonusList::firstOrNew([
            'bonus_setting_id' => $model->id,
        ]);
        $all_bonus->save();
    }
}
