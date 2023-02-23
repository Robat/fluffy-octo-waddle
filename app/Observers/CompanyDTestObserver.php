<?php

namespace App\Observers;

use App\Models\CompanyDDiff;
use App\Models\CompanyDTest;

class CompanyDTestObserver
{
    // public function retrieved
    public function creating(CompanyDTest $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }

    public function created(CompanyDTest $model)
    {
        $DTestCount = $model->select('id', 'frequency_id', 'bonus_calculation_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->where('bonus_calculation_id', $model->bonus_calculation_id)->orderBy('sort', 'ASC')->get()->toArray();


        if (count($DTestCount) > 0) {
            for ($i = 0; $i < count($DTestCount); $i++) {
                if ($i == 0) { // 判斷排序問題 diff 排序為2,4,6,8,10
                    CompanyDDiff::updateOrCreate(
                        [
                            'name' => $DTestCount[0]['name'],
                            'sort' => 0,
                            'bonus_calculation_id' => $model->bonus_calculation_id
                        ]
                    );
                } else {
                    CompanyDDiff::updateOrCreate(
                        [
                            'name' => $DTestCount[$i - 1]['name'] . '_' . $DTestCount[$i]['name'],
                            'sort' => $i * 2,
                            'bonus_calculation_id' => $model->bonus_calculation_id
                        ]
                    );
                }
            }
            CompanyDDiff::updateOrCreate(
                [
                    'name' => $DTestCount[count($DTestCount) - 1]['name'],
                    'sort' => count($DTestCount) * 2,
                    'bonus_calculation_id' => $model->bonus_calculation_id
                ]
            );
        }
    }

    public function updated(CompanyDTest $model)
    {


        $CompanyDDiffIds = CompanyDDiff::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyDDiffIds  as $CompanyDDiffId) {
            $CompanyDDiff  = CompanyDDiff::find($CompanyDDiffId)->delete();
        }

        $DTestCount = $model->select('id', 'frequency_id', 'name', 'sort', 'bonus_calculation_id')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->where('bonus_calculation_id', $model->bonus_calculation_id)->orderBy('sort', 'ASC')->get()->toArray();


        if (count($DTestCount) > 0) {
            for ($i = 0; $i < count($DTestCount); $i++) {
                if ($i == 0) {
                    CompanyDDiff::updateOrCreate(
                        [
                            'name' => $DTestCount[0]['name'],
                            'sort' => 0,

                            'bonus_calculation_id' => $model->bonus_calculation_id
                        ]
                    );
                } else {
                    CompanyDDiff::updateOrCreate(
                        [
                            'name' => $DTestCount[$i - 1]['name'] . '_' . $DTestCount[$i]['name'],
                            'sort' => $i * 2,

                            'bonus_calculation_id' => $model->bonus_calculation_id
                        ]
                    );
                }
            }
            CompanyDDiff::updateOrCreate(
                [
                    'name' => $DTestCount[count($DTestCount) - 1]['name'],
                    'sort' => count($DTestCount) * 2,

                    'bonus_calculation_id' => $model->bonus_calculation_id
                ]
            );
        }
    }


    public function delete(CompanyDTest $model)
    {
        $CompanyDDiffIds = CompanyDDiff::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyDDiffIds  as $CompanyDDiffId) {
            $CompanyDDiff  = CompanyDDiff::find($CompanyDDiffId)->delete();
        }
        $DTestCount = $model->select('id', 'frequency_id', 'name', 'sort', 'bonus_calculation_id')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->where('bonus_calculation_id', $model->bonus_calculation_id)->orderBy('sort', 'ASC')->get()->toArray();

        if (count($DTestCount) > 0) {
            for ($i = 0; $i < count($DTestCount); $i++) {
                if ($i == 0) {
                    CompanyDDiff::updateOrCreate(

                        [
                            'name' => $DTestCount[0]['name'],
                            'sort' => 0,

                            'bonus_calculation_id' => $model->bonus_calculation_id
                        ]
                    );
                } else {
                    CompanyDDiff::updateOrCreate(

                        [
                            'name' => $DTestCount[$i - 1]['name'] . '_' . $DTestCount[$i]['name'],
                            'sort' => $i * 2,

                            'bonus_calculation_id' => $model->bonus_calculation_id
                        ]
                    );
                }
            }
            CompanyDDiff::updateOrCreate(

                [
                    'name' => $DTestCount[count($DTestCount) - 1]['name'],
                    'sort' => count($DTestCount) * 2,

                    'bonus_calculation_id' => $model->bonus_calculation_id
                ]
            );
        }
    }
}
