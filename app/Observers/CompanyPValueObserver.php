<?php

namespace App\Observers;

use App\Models\CompanyPValue;
use App\Models\CompanyPTest;

class CompanyPValueObserver
{
    public function creating(CompanyPValue $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }

    //建立模擬時需加入獎金計算ID值
    public function created(CompanyPValue $model)
    {

        $CompanyPTestIds = CompanyPTest::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyPTestIds  as $CompanyPTestId) {
            $CompanyPTest  = CompanyPTest::find($CompanyPTestId)->delete();
        }

        $PValueCount = $model->select('id', 'frequency_id', 'bonus_calculation_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $model->bonus_calculation_id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

        if (count($PValueCount) > 0) {
            for ($i = 0; $i < count($PValueCount); $i++) {
                if ($i == 0) {
                    CompanyPTest::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' =>  'Over',
                            'sort' => 0 * 2 + 1
                        ]
                    );
                } else {
                    $name = intval(count($PValueCount)) - intval($i);
                    CompanyPTest::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => 'Salary Range Q' . $name . '(' . $PValueCount[$i - 1]['name'] . '<= P value <' . $PValueCount[$i]['name'] . ')',
                            'sort' => $i * 2 + 1
                        ]
                    );
                }
            }

            CompanyPTest::updateOrCreate(
                [
                    'bonus_calculation_id' => $model->bonus_calculation_id,
                    'name' => 'Below',
                    'sort' => count($PValueCount) * 2 + 1
                ]
            );
        }
    }


    public function updated(CompanyPValue $model)
    {



        $CompanyPTestIds = CompanyPTest::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyPTestIds  as $CompanyPTestId) {
            $CompanyPTest  = CompanyPTest::find($CompanyPTestId)->delete();
        }

        $PValueCount = $model->select('id', 'frequency_id', 'bonus_calculation_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $model->bonus_calculation_id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

        if (count($PValueCount) > 0) {
            for ($i = 0; $i < count($PValueCount); $i++) {
                if ($i == 0) {
                    CompanyPTest::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' =>  'Over',
                            'sort' => 0 * 2 + 1
                        ]
                    );
                } else {
                    $name = intval(count($PValueCount)) - intval($i);
                    CompanyPTest::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => 'Salary Range Q' . $name . '(' . $PValueCount[$i - 1]['name'] . '<= P value <' . $PValueCount[$i]['name'] . ')',
                            'sort' => $i * 2 + 1
                        ]
                    );
                }
            }

            CompanyPTest::updateOrCreate(
                [
                    'bonus_calculation_id' => $model->bonus_calculation_id,
                    'name' => 'Below',
                    'sort' => count($PValueCount) * 2 + 1
                ]
            );
        }
    }

    public function deleted(CompanyPValue $model)
    {
        $CompanyPTestIds = CompanyPTest::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyPTestIds  as $CompanyPTestId) {
            $CompanyPTest  = CompanyPTest::find($CompanyPTestId)->delete();
        }

        $PValueCount = $model->select('id', 'frequency_id', 'bonus_calculation_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $model->bonus_calculation_id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

        if (count($PValueCount) > 0) {
            for ($i = 0; $i < count($PValueCount); $i++) {
                if ($i == 0) {
                    CompanyPTest::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' =>  'Over',
                            'sort' => 0 * 2 + 1
                        ]
                    );
                } else {
                    $name = intval(count($PValueCount)) - intval($i);
                    CompanyPTest::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => 'Salary Range Q' . $name . '(' . $PValueCount[$i - 1]['name'] . '<= P value <' . $PValueCount[$i]['name'] . ')',
                            'sort' => $i * 2 + 1
                        ]
                    );
                }
            }

            CompanyPTest::updateOrCreate(
                [
                    'bonus_calculation_id' => $model->bonus_calculation_id,
                    'name' => 'Below',
                    'sort' => count($PValueCount) * 2 + 1
                ]
            );
        }
    }
}
