<?php

namespace App\Observers;

use App\Models\ATest;
use App\Models\ADiff;

class ATestObserver
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

    public function creating(ATest $model)
    {
        if (admin() && \admin()->type == 'admin') {


            $model->frequency_id = admin()->company->frequency()->id;
        }
    }

    public function created(ATest $model)
    {

        $ATestCount = $model->select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();


        if (count($ATestCount) > 0) {
            for ($i = 0; $i < count($ATestCount); $i++) {
                if ($i == 0) {
                    ADiff::updateOrCreate(
                        ['slug' => $ATestCount[0]['frequency_id'] . '_' . '0'],
                        [
                            'name' => $ATestCount[0]['name'],
                            'sort' => 0
                        ]
                    );
                } else {
                    ADiff::updateOrCreate(
                        ['slug' => $ATestCount[0]['frequency_id'] . '_' . $i],
                        [
                            'name' => $ATestCount[$i - 1]['name'] . '_' . $ATestCount[$i]['name'],
                            'sort' => $i * 2
                        ]
                    );
                }
            }
            ADiff::updateOrCreate(
                ['slug' => $ATestCount[0]['frequency_id'] . '_' . count($ATestCount)],
                [
                    'name' => $ATestCount[count($ATestCount) - 1]['name'],
                    'sort' => count($ATestCount) * 2
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
