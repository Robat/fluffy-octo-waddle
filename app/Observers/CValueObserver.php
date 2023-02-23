<?php

namespace App\Observers;

use App\Models\CValue;
use App\Models\CTest;

class CValueObserver
{
    public function creating(CValue $model)
    {
        if (admin() && \admin()->type == 'admin') {
            $model->frequency_id = admin()->company->frequency()->id;
        }
    }


    public function created(CValue $model)
    {
        $CValueCount = $model->select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

        if (count($CValueCount) > 0) {
            for ($i = 0; $i < count($CValueCount); $i++) {
                if ($i == 0) {
                    CTest::updateOrCreate(
                        ['slug' => $CValueCount[0]['frequency_id'] . '_' . '0'],
                        [
                            'name' =>  'CR >=' . $CValueCount[0]['name'],
                            'sort' => 0 * 2 + 1
                        ]
                    );
                } else {
                    CTest::updateOrCreate(
                        ['slug' => $CValueCount[0]['frequency_id'] . '_' . $i],
                        [
                            'name' => $CValueCount[$i - 1]['name'] . '<= CR <' . $CValueCount[$i]['name'],
                            'sort' => $i * 2 + 1
                        ]
                    );
                }
            }

            CTest::updateOrCreate(
                ['slug' => $CValueCount[0]['frequency_id'] . '_' . count($CValueCount)],
                [
                    'name' => 'CR <' . $CValueCount[count($CValueCount) - 1]['name'],
                    'sort' => count($CValueCount) * 2 + 1
                ]
            );
        }
    }
}
