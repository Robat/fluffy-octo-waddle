<?php

namespace App\Observers;

use App\Models\PValue;
use App\Models\PTest;

class PValueObserver
{
    public function creating(PValue $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }


    public function created(PValue $model)
    {
        $PValueCount = $model->select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

        if (count($PValueCount) > 0) {
            for ($i = 0; $i < count($PValueCount); $i++) {

                if ($i == 0) {
                    PTest::updateOrCreate(
                        ['slug' => $PValueCount[0]['frequency_id'] . '_' . '0'],
                        [
                            'name' => 'Over',
                            'sort' =>  0 * 2 + 1
                        ]
                    );
                } else {
                    $name = intval(count($PValueCount)) - intval($i);
                    PTest::updateOrCreate(
                        ['slug' => $PValueCount[0]['frequency_id'] . '_' . $i],
                        [
                            'name' => 'Salary Range Q' . $name,
                            'sort' => $i * 2 + 1
                        ]
                    );
                }
            }

            PTest::updateOrCreate(
                ['slug' => $PValueCount[0]['frequency_id'] . '_' . count($PValueCount)],
                [
                    'name' => 'Below',
                    'sort' => count($PValueCount) * 2 + 1
                ]
            );
        }
    }

    // public function deleted(PValue $model)
    // {
    //     $PValueCount = $model->where('frequency_id', admin()->company->frequency()->id)->count();
    //     $PTestCount = PTest::where('frequency_id', admin()->company->frequency()->id)->count();
    //     if ($PTestCount - $PValueCount > 1) {
    //         for ($i = $PTestCount; $i > $PValueCount; $i--) {
    //             PTest::where('frequency_id', admin()->company->frequency()->id)->where('sort', '=',  $i)->delete();
    //         }
    //     }
    //     if (!$PValueCount) {
    //         for ($i = 1; $i <= $PTestCount; $i++) {
    //             PTest::where('frequency_id', admin()->company->frequency()->id)->where('sort', '=',  $i)->delete();
    //         }
    //     }
    // }
}
