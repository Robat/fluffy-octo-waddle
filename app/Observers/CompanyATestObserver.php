<?php

namespace App\Observers;

use App\Models\CompanyADiff;
use App\Models\CompanyATest;
use App\Models\CompanyATestADiff;
use App\Models\CompanyATestATest;

class CompanyATestObserver
{
    // public function retrieved(ATest $model)
    // {
    //     $ATestCount = $model->where('frequency_id', admin()->company->frequency()->id)->count();

    //     $ADiffCount = ADiff::where('frequency_id', admin()->company->frequency()->id)->count();

    //     if ($ADiffCount - $ATestCount > 1) {
    //         for ($i = $ADiffCount; $i > $ATestCount; $i--) {
    //             ADiff::where('frequency_id', admin()->company->frequency()->id)->where('name', '=', 'A_' . $i)->delete();
    //         }
    //     };
    //     if (!$ATestCount) {
    //         for ($i = 0; $i < $ADiffCount; $i++) {
    //             ADiff::where('frequency_id', admin()->company->frequency()->id)->where('name', '=', 'A_' . $i)->delete();
    //         }
    //     }
    // }

    public function creating(CompanyATest $model)
    {
        if (admin() && \admin()->type == 'admin') {


            $model->frequency_id = admin()->company->frequency()->id;
        }
    }

    public function created(CompanyATest $model)
    {
        $ATestCount = $model->select('id', 'frequency_id', 'bonus_calculation_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->where('bonus_calculation_id', $model->bonus_calculation_id)->orderBy('sort', 'ASC')->get()->toArray();


        if (count($ATestCount) > 0) {
            for ($i = 0; $i < count($ATestCount); $i++) {
                if ($i == 0) {
                    CompanyADiff::updateOrCreate(
                        [
                            'name' => $ATestCount[0]['name'],
                            'sort' => 0,

                            'bonus_calculation_id' => $model->bonus_calculation_id
                        ]
                    );
                } else {
                    CompanyADiff::updateOrCreate(
                        [
                            'name' => $ATestCount[$i - 1]['name'] . '_' . $ATestCount[$i]['name'],
                            'sort' => $i * 2,

                            'bonus_calculation_id' => $model->bonus_calculation_id
                        ]
                    );
                }
            }
            CompanyADiff::updateOrCreate(
                [
                    'name' => $ATestCount[count($ATestCount) - 1]['name'],
                    'sort' => count($ATestCount) * 2,

                    'bonus_calculation_id' => $model->bonus_calculation_id
                ]
            );
        }
    }

    public function updated(CompanyATest $model)
    {

        $CompanyADiffIds = CompanyADiff::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyADiffIds  as $CompanyADiffId) {
            $CompanyADiff  = CompanyADiff::find($CompanyADiffId)->delete();
        }

        $ATestCount = $model->select('id', 'frequency_id', 'bonus_calculation_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->where('bonus_calculation_id', $model->bonus_calculation_id)->orderBy('sort', 'ASC')->get()->toArray();


        if (count($ATestCount) > 0) {
            for ($i = 0; $i < count($ATestCount); $i++) {
                if ($i == 0) {
                    CompanyADiff::updateOrCreate(
                        [
                            'name' => $ATestCount[0]['name'],
                            'sort' => 0,

                            'bonus_calculation_id' => $model->bonus_calculation_id
                        ]
                    );
                } else {
                    CompanyADiff::updateOrCreate(
                        [
                            'name' => $ATestCount[$i - 1]['name'] . '_' . $ATestCount[$i]['name'],
                            'sort' => $i * 2,

                            'bonus_calculation_id' => $model->bonus_calculation_id
                        ]
                    );
                }
            }
            CompanyADiff::updateOrCreate(
                [
                    'name' => $ATestCount[count($ATestCount) - 1]['name'],
                    'sort' => count($ATestCount) * 2,

                    'bonus_calculation_id' => $model->bonus_calculation_id
                ]
            );
        }
    }

    public function delete(CompanyATest $model)
    {

        $CompanyADiffIds = CompanyADiff::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyADiffIds  as $CompanyADiffId) {
            $CompanyADiff  = CompanyADiff::find($CompanyADiffId)->delete();
        }

        $ATestCount = $model->select('id', 'frequency_id', 'bonus_calculation_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->where('bonus_calculation_id', $model->bonus_calculation_id)->orderBy('sort', 'ASC')->get()->toArray();


        if (count($ATestCount) > 0) {
            for ($i = 0; $i < count($ATestCount); $i++) {
                if ($i == 0) {
                    CompanyADiff::updateOrCreate(
                        [
                            'name' => $ATestCount[0]['name'],
                            'sort' => 0,

                            'bonus_calculation_id' => $model->bonus_calculation_id
                        ]
                    );
                } else {
                    CompanyADiff::updateOrCreate(
                        [
                            'name' => $ATestCount[$i - 1]['name'] . '_' . $ATestCount[$i]['name'],
                            'sort' => $i * 2,

                            'bonus_calculation_id' => $model->bonus_calculation_id
                        ]
                    );
                }
            }
            CompanyADiff::updateOrCreate(
                [
                    'name' => $ATestCount[count($ATestCount) - 1]['name'],
                    'sort' => count($ATestCount) * 2,

                    'bonus_calculation_id' => $model->bonus_calculation_id
                ]
            );
        }
    }

    // public function deleted(ATest $model)
    // {
    //     $ATestCount = $model->select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();
    //     $ADiffCount = ADiff::where('frequency_id', admin()->company->frequency()->id)->count();
    //     if ($ADiffCount - count($ATestCount) > 1) {
    //         for ($i = $ADiffCount; $i > count($ATestCount); $i--) {
    //             ADiff::where('frequency_id', admin()->company->frequency()->id)->where('name', '=', 'A_' . $i)->delete();
    //         }
    //     }
    //     if (!count($ATestCount)) {
    //         for ($i = 0; $i < $ADiffCount; $i++) {
    //             ADiff::where('frequency_id', admin()->company->frequency()->id)->where('name', '=', 'A_' . $i)->delete();
    //         }
    //     }
    // }
}
