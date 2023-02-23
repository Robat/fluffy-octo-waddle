<?php

namespace App\Observers;

use App\Models\CompanyPDiff;
use App\Models\CompanyPTest;
use App\Models\CompanyPValue;

class CompanyPTestObserver
{
    public function creating(CompanyPTest $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }


    public function created(CompanyPTest $model)
    {
        $CompanyPDiffIds = CompanyPDiff::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyPDiffIds  as $CompanyPDiffId) {
            $CompanyPDiff  = CompanyPDiff::find($CompanyPDiffId)->delete();
        }

        $PTestCount = $model->select('id', 'frequency_id', 'name', 'sort', 'numbering', 'bonus_calculation_id')->where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $model->bonus_calculation_id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();
        if (count($PTestCount) > 0) {
            for ($i = 0; $i < count($PTestCount); $i++) {
                if ($i == 0) {
                    CompanyPDiff::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $PTestCount[0]['name'],
                            'sort' => 0 * 2
                        ]
                    );
                } else {
                    CompanyPDiff::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $PTestCount[$i - 1]['name'] . ' ' . $PTestCount[$i]['name'],
                            'numbering' => 1,
                            'sort' => $i * 2
                        ]
                    );
                }
            }

            CompanyPDiff::updateOrCreate(
                [
                    'bonus_calculation_id' => $model->bonus_calculation_id,
                    'name' => $PTestCount[count($PTestCount) - 1]['name'],
                    'sort' => count($PTestCount) * 2
                ]
            );
        }
    }

    public function updated(CompanyPTest $model)
    {

        $CompanyPDiffIds = CompanyPDiff::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyPDiffIds  as $CompanyPDiffId) {
            $CompanyPDiff  = CompanyPDiff::find($CompanyPDiffId)->delete();
        }
        $PTestCount = $model->select('id', 'frequency_id', 'name', 'sort', 'numbering', 'bonus_calculation_id')->where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $model->bonus_calculation_id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();
        if (count($PTestCount) > 0) {
            for ($i = 0; $i < count($PTestCount); $i++) {
                if ($i == 0) {
                    CompanyPDiff::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $PTestCount[0]['name'],
                            'sort' => 0 * 2
                        ]
                    );
                } else {
                    CompanyPDiff::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $PTestCount[$i - 1]['name'] . ' ' . $PTestCount[$i]['name'],
                            'numbering' => 1,
                            'sort' => $i * 2
                        ]
                    );
                }
            }

            CompanyPDiff::updateOrCreate(
                [
                    'bonus_calculation_id' => $model->bonus_calculation_id,
                    'name' => $PTestCount[count($PTestCount) - 1]['name'],
                    'sort' => count($PTestCount) * 2
                ]
            );
        }
    }


    public function delete(CompanyPTest $model)
    {
        $CompanyPDiffIds = CompanyPDiff::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyPDiffIds  as $CompanyPDiffId) {
            $CompanyPDiff  = CompanyPDiff::find($CompanyPDiffId)->delete();
        }
        $PTestCount = $model->select('id', 'frequency_id', 'name', 'sort', 'numbering', '')->where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $model->bonus_calculation_id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();
        if (count($PTestCount) > 0) {
            for ($i = 0; $i < count($PTestCount); $i++) {
                if ($i == 0) {
                    CompanyPDiff::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $PTestCount[0]['name'],
                            'sort' => 0 * 2
                        ]
                    );
                } else {
                    CompanyPDiff::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $PTestCount[$i - 1]['name'] . ' ' . $PTestCount[$i]['name'],
                            'numbering' => 1,
                            'sort' => $i * 2
                        ]
                    );
                }
            }

            CompanyPDiff::updateOrCreate(
                [
                    'bonus_calculation_id' => $model->bonus_calculation_id,
                    'name' => $PTestCount[count($PTestCount) - 1]['name'],
                    'sort' => count($PTestCount) * 2
                ]
            );
        }
    }
}
