<?php

namespace App\Observers;

use App\Models\CompanyCValue;
use App\Models\CompanyCTest;

class CompanyCValueObserver
{
    public function creating(CompanyCValue $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }
    //建立模擬時需加入獎金計算ID值
    public function created(CompanyCValue $model)
    {
        $CValueCount = $model->select('id', 'frequency_id', 'bonus_calculation_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $model->bonus_calculation_id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

        if (count($CValueCount) > 0) {
            for ($i = 0; $i < count($CValueCount); $i++) {
                if ($i == 0) {
                    CompanyCTest::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' =>  'CR >=' . $CValueCount[0]['name'],
                            'sort' => 0 * 2 + 1
                        ]
                    );
                } else {
                    CompanyCTest::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $CValueCount[$i - 1]['name'] . '<= CR <' . $CValueCount[$i]['name'],
                            'sort' => $i * 2 + 1
                        ]
                    );
                }
            }

            CompanyCTest::updateOrCreate(
                [
                    'bonus_calculation_id' => $model->bonus_calculation_id,
                    'name' => 'CR <' . $CValueCount[count($CValueCount) - 1]['name'],
                    'sort' => count($CValueCount) * 2 + 1
                ]
            );
        }
    }

    public function updated(CompanyCValue $model)
    {
        $CompanyCTestIds = CompanyCTest::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyCTestIds  as $CompanyCTestId) {
            $CompanyCTest  = CompanyCTest::find($CompanyCTestId)->delete();
        }

        $CValueCount = $model->select('id', 'frequency_id', 'bonus_calculation_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $model->bonus_calculation_id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

        if (count($CValueCount) > 0) {
            for ($i = 0; $i < count($CValueCount); $i++) {
                if ($i == 0) {
                    CompanyCTest::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' =>  'CR >=' . $CValueCount[0]['name'],
                            'sort' => 0 * 2 + 1
                        ]
                    );
                } else {
                    CompanyCTest::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $CValueCount[$i - 1]['name'] . '<= CR <' . $CValueCount[$i]['name'],
                            'sort' => $i * 2 + 1
                        ]
                    );
                }
            }

            CompanyCTest::updateOrCreate(
                [
                    'bonus_calculation_id' => $model->bonus_calculation_id,
                    'name' => 'CR <' . $CValueCount[count($CValueCount) - 1]['name'],
                    'sort' => count($CValueCount) * 2 + 1
                ]
            );
        }
    }

    public function deleted(CompanyCValue $model)
    {
        $CompanyCTestIds = CompanyCTest::where('bonus_calculation_id', $model->bonus_calculation_id)->pluck('id');
        foreach ($CompanyCTestIds  as $CompanyCTestId) {
            $CompanyCTest  = CompanyCTest::find($CompanyCTestId)->delete();
        }

        $CValueCount = $model->select('id', 'frequency_id', 'bonus_calculation_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $model->bonus_calculation_id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

        if (count($CValueCount) > 0) {
            for ($i = 0; $i < count($CValueCount); $i++) {
                if ($i == 0) {
                    CompanyCTest::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' =>  'CR >=' . $CValueCount[0]['name'],
                            'sort' => 0 * 2 + 1
                        ]
                    );
                } else {
                    CompanyCTest::updateOrCreate(
                        [
                            'bonus_calculation_id' => $model->bonus_calculation_id,
                            'name' => $CValueCount[$i - 1]['name'] . '<= CR <' . $CValueCount[$i]['name'],
                            'sort' => $i * 2 + 1
                        ]
                    );
                }
            }

            CompanyCTest::updateOrCreate(
                [
                    'bonus_calculation_id' => $model->bonus_calculation_id,
                    'name' => 'CR <' . $CValueCount[count($CValueCount) - 1]['name'],
                    'sort' => count($CValueCount) * 2 + 1
                ]
            );
        }
    }
}
