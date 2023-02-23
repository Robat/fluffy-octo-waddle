<?php

namespace App\Observers;

use App\Models\ATest;
use App\Models\CompanyPerformanceBonus;
use App\Models\CompanyPerformanceBonusList;

class CompanyPerformanceBonusListObserver
{
    public function creating(CompanyPerformanceBonusList $model)
    {
        if (admin() && \admin()->type == 'admin') {

            $model->frequency_id = admin()->company->frequency()->id;
        }
    }

    public function created(CompanyPerformanceBonusList $model)
    {
        // 預設寫入 ATest 績效範圍

        $atests = ATest::where('frequency_id', admin()->company->frequency()->id)->get();



        foreach ($atests as $key => $value) {
            $performance = CompanyPerformanceBonus::firstOrNew([
                'frequency_id' => $value->frequency_id,
                'name' => $value->name,
                'sort' => $value->sort,
                'performance_id' => $model->id
            ]);
            $performance->save();
        }
    }
}
