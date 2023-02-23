<?php

namespace App\Observers;

use App\Models\CompanyCDiff;
use App\Models\CompanyCTest;
use App\Models\CompanyCValue;

class CompanyCTestObserver
{
    public function creating(CompanyCTest $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }


    public function created(CompanyCTest $model)
    {
        $CompanyCDiffIds = CompanyCDiff::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyCDiffIds  as $CompanyCDiffId) {
            $CompanyCDiff  = CompanyCDiff::find($CompanyCDiffId)->delete();
        }

        $CTestCount = $model->select('id', 'frequency_id', 'name', 'sort', 'numbering')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->where('bonus_calculation_id', $model->bonus_calculation_id)->orderBy('sort', 'ASC')->get()->toArray();
        if (count($CTestCount) > 0) {
            for ($i = 0; $i < count($CTestCount); $i++) {
                if ($i == 0) {
                    CompanyCDiff::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $CTestCount[0]['name'],
                            'sort' => 0 * 2
                        ]
                    );
                } else {
                    CompanyCDiff::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $CTestCount[$i - 1]['name'] . ' ' . $CTestCount[$i]['name'],
                            'numbering' => 1,
                            'sort' => $i * 2
                        ]
                    );
                }
            }

            CompanyCDiff::updateOrCreate(
                [
                    'bonus_calculation_id' => $model->bonus_calculation_id,
                    'name' => $CTestCount[count($CTestCount) - 1]['name'],
                    'sort' => count($CTestCount) * 2
                ]
            );
        }
    }

    public function updated(CompanyCTest $model)
    {

        $CompanyCDiffIds = CompanyCDiff::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyCDiffIds  as $CompanyCDiffId) {
            $CompanyCDiff  = CompanyCDiff::find($CompanyCDiffId)->delete();
        }
        $CTestCount = $model->select('id', 'frequency_id', 'name', 'sort', 'numbering')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->where('bonus_calculation_id', $model->bonus_calculation_id)->orderBy('sort', 'ASC')->get()->toArray();
        if (count($CTestCount) > 0) {
            for ($i = 0; $i < count($CTestCount); $i++) {
                if ($i == 0) {
                    CompanyCDiff::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $CTestCount[0]['name'],
                            'sort' => 0 * 2
                        ]
                    );
                } else {
                    CompanyCDiff::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $CTestCount[$i - 1]['name'] . ' ' . $CTestCount[$i]['name'],
                            'numbering' => 1,
                            'sort' => $i * 2
                        ]
                    );
                }
            }

            CompanyCDiff::updateOrCreate(
                [
                    'bonus_calculation_id' => $model->bonus_calculation_id,
                    'name' => $CTestCount[count($CTestCount) - 1]['name'],
                    'sort' => count($CTestCount) * 2
                ]
            );
        }
    }


    public function delete(CompanyCTest $model)
    {
        $CompanyCDiffIds = CompanyCDiff::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyCDiffIds  as $CompanyCDiffId) {
            $CompanyCDiff  = CompanyCDiff::find($CompanyCDiffId)->delete();
        }
        $CTestCount = $model->select('id', 'frequency_id', 'name', 'sort', 'numbering')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->where('bonus_calculation_id', $model->bonus_calculation_id)->orderBy('sort', 'ASC')->get()->toArray();
        if (count($CTestCount) > 0) {
            for ($i = 0; $i < count($CTestCount); $i++) {
                if ($i == 0) {
                    CompanyCDiff::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $CTestCount[0]['name'],
                            'sort' => 0 * 2
                        ]
                    );
                } else {
                    CompanyCDiff::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $CTestCount[$i - 1]['name'] . ' ' . $CTestCount[$i]['name'],
                            'numbering' => 1,
                            'sort' => $i * 2
                        ]
                    );
                }
            }

            CompanyCDiff::updateOrCreate(
                [
                    'bonus_calculation_id' => $model->bonus_calculation_id,
                    'name' => $CTestCount[count($CTestCount) - 1]['name'],
                    'sort' => count($CTestCount) * 2
                ]
            );
        }
    }
}
