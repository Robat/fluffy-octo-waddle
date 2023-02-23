<?php

namespace App\Observers;

use App\Models\ATest;
use App\Models\CompanyEvaluatingBonus;
use App\Models\CompanyEvaluatingBonusList;

class CompanyEvaluatingBonusListObserver
{
    public function creating(CompanyEvaluatingBonusList $model)
    {
        if (admin() && \admin()->type == 'admin') {

            $model->frequency_id = admin()->company->frequency()->id;
        }
    }

    public function created(CompanyEvaluatingBonusList $model)
    {
        // 預設寫入 ATest 績效範圍

        $atests = ATest::where('frequency_id', admin()->company->frequency()->id)->get();



        foreach ($atests as $key => $value) {
            $evaluating = CompanyEvaluatingBonus::firstOrNew([
                'frequency_id' => $value->frequency_id,
                'name' => $value->name,
                'sort' => $value->sort,
                'evaluating_id' => $model->id
            ]);
            $evaluating->save();
        }
    }
}
