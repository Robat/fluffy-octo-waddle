<?php

namespace App\Observers;

use App\Models\ADiff;
use App\Models\ATest;
use App\Models\CompanyADiff;
use App\Models\CompanyATest;
use App\Models\CompanyDDiff;
use App\Models\CompanyDTest;
use App\Models\CompanyEvaluatingBonus;
use App\Models\CompanyBonusCalculation;
use App\Models\CompanyPerformanceBonus;

class CompanyBonusCalculationObserver
{
    public function creating(CompanyBonusCalculation $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }


    public function created(CompanyBonusCalculation $model)
    {
        $data = ATest::select('name', 'frequency_id', 'sort', 'numbering', 'rank_from', 'rank_to')->where('status', 1)->get()->toArray();

        $data2 = ADiff::select('name', 'slug', 'sort', 'numbering', 'status')->where('status', 1)->get()->toArray();

        // 使用 ATEST 績效
        $new_data = [];
        $evaluating_new_data = [];

        $atest_new_data = [];
        $adiff_new_data = [];
        $dtest_new_data = [];
        $ddiff_new_data = [];

        // CValue
        $cvalue_new_data = [];

        // PValue
        $pvalue_new_data = [];

        foreach ($data  as $key => $value) {
            array_push($new_data, array(
                "name" => $value['name'],
                "rank_from" => $value['rank_from'],
                "rank_to" => $value['rank_to'],
                "numbering" => $value['numbering'],
                "sort" => $value['sort'],
                "frequency_id" => admin()->company->frequency()->id,
                "calculation_id" => $model->id,
            ));
        }

        foreach ($data  as $key => $value) {
            array_push($evaluating_new_data, array(
                "name" => $value['name'],
                "numbering" => $value['numbering'],
                "sort" => $value['sort'],
                "frequency_id" => admin()->company->frequency()->id,
                "calculation_id" => $model->id,
            ));
        }


        foreach ($data  as $key => $value) {
            array_push($atest_new_data, array(
                "name" => $value['name'],
                "numbering" => $value['numbering'],
                "sort" => $value['sort'],
                "frequency_id" => admin()->company->frequency()->id,
                "bonus_calculation_id" => $model->id,
            ));
        }

        foreach ($data  as $key => $value) {
            array_push($dtest_new_data, array(
                "name" => $value['name'],
                "numbering" => $value['numbering'],
                "sort" => $value['sort'],
                "frequency_id" => admin()->company->frequency()->id,
                "bonus_calculation_id" => $model->id,
            ));
        }

        foreach ($data2  as $key => $value) {
            array_push($adiff_new_data, array(
                "name" => $value['name'],
                "numbering" => $value['numbering'],
                "sort" => $value['sort'],
                "frequency_id" => admin()->company->frequency()->id,
                "bonus_calculation_id" => $model->id,
            ));
        }

        foreach ($data2  as $key => $value) {
            array_push($ddiff_new_data, array(
                "name" => $value['name'],
                "numbering" => $value['numbering'],
                "sort" => $value['sort'],
                "frequency_id" => admin()->company->frequency()->id,
                "bonus_calculation_id" => $model->id,
            ));
        }

        CompanyPerformanceBonus::insert($new_data);
        CompanyEvaluatingBonus::insert($evaluating_new_data);

        CompanyATest::insert($atest_new_data);
        CompanyDTest::insert($dtest_new_data);

        CompanyADiff::insert($adiff_new_data);
        CompanyDDiff::insert($ddiff_new_data);



        // $evaluating = CompanyEvaluatingBonusList::firstOrNew([
        //     'bonus_setting_id' => $model->id,
        // ]);
        // $evaluating->name = '模擬用';
        // $evaluating->save();
    }
}
