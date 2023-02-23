<?php

namespace App\Observers;

use App\Models\ADiff;
use App\Models\ATest;
use App\Models\ATestCTest;
use App\Models\CDiff;
use App\Models\CTest;
use App\Models\CompanyCenterPoint;

class CTestObserver

{
    // public function retrieved(CTest $model)
    // {
    //     $CTestCount = $model->where('frequency_id', admin()->company->frequency()->id)->count();

    //     $CDiffCount = CDiff::where('frequency_id', admin()->company->frequency()->id)->count();

    //     if ($CTestCount > 0) {
    //         for ($i = 1; $i <= $CTestCount + 1; $i++) {
    //             CDiff::updateOrCreate(
    //                 ['sort' =>  $i],
    //             );
    //         }
    //     }

    //     if ($CDiffCount - $CTestCount > 1) {
    //         for ($i = $CDiffCount; $i > $CTestCount; $i--) {
    //             CDiff::where('frequency_id', admin()->company->frequency()->id)->where('sort', '=',  $i)->delete();
    //         }
    //     };

    //     if (!$CTestCount) {
    //         for ($i = 1; $i <= $CDiffCount; $i++) {
    //             CDiff::where('frequency_id', admin()->company->frequency()->id)->where('sort', '=',  $i)->delete();
    //         }
    //     }
    // }

    public function creating(CTest $model)
    {
        if (admin() && \admin()->type == 'admin') {


            $model->frequency_id = admin()->company->frequency()->id;
        }
    }


    public function created(CTest $model)
    {
        $CTestCount = $model->select('id', 'frequency_id', 'name', 'sort', 'numbering')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();


        if (count($CTestCount) > 0) {
            for ($i = 0; $i < count($CTestCount); $i++) {
                if ($i == 0) {
                    CDiff::updateOrCreate(
                        ['slug' => $CTestCount[0]['frequency_id'] . '_' . '0'],
                        [
                            'name' => $CTestCount[0]['name'],
                            'sort' => 0 * 2
                        ]
                    );
                } else {
                    CDiff::updateOrCreate(
                        ['slug' => $CTestCount[0]['frequency_id'] . '_' . $i],
                        [
                            'name' => $CTestCount[$i - 1]['name'] . ' ' . $CTestCount[$i]['name'],
                            'numbering' => 1,
                            'sort' => $i * 2
                        ]
                    );
                }
            }

            CDiff::updateOrCreate(
                ['slug' => $CTestCount[0]['frequency_id'] . '_' . count($CTestCount)],
                [
                    'name' => $CTestCount[count($CTestCount) - 1]['name'],
                    'sort' => count($CTestCount) * 2
                ]
            );
        }


        // 新增預設中心點 2.5
        $center_point = 2.5;


        $ATestCount = ATest::select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();
        $a_point = intval(count($ATestCount) / 2) * 2 + 1;

        $CTestCount = CTest::select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

        $c_point = intval(count($CTestCount) / 2) * 2 + 1;

        CompanyCenterPoint::updateOrCreate(['frequency_id' => admin()->company->frequency()->id, 'category' => 'c'], ['a_point' => $a_point, 'c_point' => $c_point, 'numbering' => $center_point]);

        //自動寫入
        $atest_all = ATest::where('frequency_id', admin()->company->frequency()->id)->pluck('sort', 'id')->toArray();


        foreach ($atest_all as $key => $value) {
            $atest_all[$key] = array(
                'frequency_id' => admin()->company->frequency()->id,
                'a_sort' => $value
            );
        }

        $ctests = CTest::where('frequency_id', admin()->company->frequency()->id)->get();

        foreach ($ctests as $ctest) {
            $ctest->a_tests()->sync($atest_all);
        }


        $ctest_all = CTest::where('frequency_id', admin()->company->frequency()->id)->pluck('sort', 'id')->toArray();
        foreach ($ctest_all as $key => $value) {
            $ctest_all[$key] = array(
                'frequency_id' => admin()->company->frequency()->id,
                'c_sort' => $value,
                'score' => 2
            );
        }

        $atests = ATest::where('frequency_id', admin()->company->frequency()->id)->get();


        foreach ($atests as  $value) {
            $value->c_tests()->sync($ctest_all);
        }

        $adiffs = ADiff::select('id', 'frequency_id', 'name', 'sort', 'numbering')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();


        $cdiffs = CDiff::select('id', 'frequency_id', 'name', 'sort', 'numbering')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

        $front = [];
        $back = [];
        $up = [];
        $down = [];
        foreach ($cdiffs as $key => $cdiff) {
            if ($c_point > $cdiff['sort']) {
                if ($key !== 0) {
                    array_push($up, $cdiff);
                }
            } else {
                if ($key !==  count($cdiffs) - 1) {
                    array_push($down, $cdiff);
                }
            }
        }

        $accumulator = [];
        $up_output = array_reverse($up);
        $new_up = array();
        $accumulator['numbering'] = $center_point;
        foreach ($up_output as $key => $value) {
            $accumulator['sort'] = $value['sort'] - 1;
            $accumulator['numbering'] -= $value['numbering'];
            array_push($new_up, $accumulator);
        }

        $accumulator = [];
        $down_output = $down;
        $new_down = array();
        $accumulator['numbering'] = $center_point;
        foreach ($down_output as $key => $value) {
            $accumulator['sort'] = $value['sort'] + 1;
            $accumulator['numbering'] += $value['numbering'];
            array_push($new_down, $accumulator);
        }

        $c_center_point_array = array(['sort' => $c_point, 'numbering' => $center_point]);

        $c_array = array_merge_recursive(array_reverse($new_up), $c_center_point_array, $new_down);


        foreach ($adiffs as $key => $adiff) {
            if ($a_point > $adiff['sort']) {
                if ($key !== 0) {
                    array_push($front, $adiff);
                }
            } else {
                if ($key !==  count($adiffs) - 1) {
                    array_push($back, $adiff);
                }
            }
        }



        $atestctest = [];
        $atestctest_1 = [];
        foreach ($c_array as $index => $item) {
            $front_output = array_reverse($front);
            $new_front = array();
            $accumulator_front['numbering'] = $item['numbering'];
            foreach ($front_output as $key => $value) {
                $accumulator_front['a_sort'] = $value['sort'] - 1;
                $accumulator_front['c_sort'] = $item['sort'];
                $accumulator_front['numbering'] += $value['numbering'];
                array_push($new_front, $accumulator_front);
            }

            $back_output = $back;
            $new_back = array();
            $accumulator_back['numbering'] = $item['numbering'];
            foreach ($back_output as $key => $value) {
                $accumulator_back['a_sort'] = $value['sort'] + 1;
                $accumulator_back['c_sort'] = $item['sort'];
                $accumulator_back['numbering'] -= $value['numbering'];
                array_push($new_back, $accumulator_back);
            }

            $a_center_point_array = array(['a_sort' => $a_point, 'c_sort' => $item['sort'], 'numbering' => $item['numbering']]);

            $a_array = array_merge_recursive(array_reverse($new_front), $a_center_point_array, $new_back);

            if ($adiffs[0]['numbering'] != '') {
                $a_array[0]['numbering'] = $adiffs[0]['numbering'];
            }
            if ($adiffs[count($adiffs) - 1]['numbering'] != '') {
                $a_array[count($a_array) - 1]['numbering'] = $adiffs[count($adiffs) - 1]['numbering'];
            }

            if ($index == 0 && $cdiffs[0]['numbering'] != null) {
                foreach ($a_array as $akey => $avalue) {
                    $a_array[$akey]['numbering'] = $cdiffs[0]['numbering'];
                }
            }

            if ($index == count($c_array) - 1 && $cdiffs[count($cdiffs) - 1]['numbering'] != null) {
                foreach ($a_array as $akey => $avalue) {
                    $a_array[$akey]['numbering'] = $cdiffs[count($cdiffs) - 1]['numbering'];
                }
            }


            array_push($atestctest, $a_array);
            $atestctest_1 = array_merge($atestctest_1, $a_array);
        }
        // dd($atestctest_1);
        // 1110 想先攤開
        $selects =  ATestCTest::select('frequency_id', 'a_sort', 'c_sort', 'score', 'location')->get()->toArray();

        // dd($selects);
        foreach ($atestctest_1 as $key => $value) {
            if ($value['a_sort'] == $selects[$key]['a_sort'] && $value['c_sort'] == $selects[$key]['c_sort']) {
                $selects[$key]['score'] = $value['numbering'];
            }
        }


        // foreach ($atestctest as $avalue) {
        //     foreach ($avalue as $value) {
        //         $t = ATestCTest::where('frequency_id', 4)->where('a_sort', $value['a_sort'])->where('c_sort', $value['c_sort'])->first();
        //         $t->score = $value['numbering'];
        //         $t->save();
        //     }
        // }


        app(ATestCTest::class)->updateBatch($selects);
    }

    // public function deleted(CTest $model)
    // {
    //     $CTestCount = $model->where('frequency_id', admin()->company->frequency()->id)->count();
    //     $CDiffCount = CDiff::where('frequency_id', admin()->company->frequency()->id)->count();
    //     if ($CDiffCount - $CTestCount > 1) {
    //         for ($i = $CDiffCount; $i > $CTestCount; $i--) {
    //             CDiff::where('frequency_id', admin()->company->frequency()->id)->where('sort', '=',  $i)->delete();
    //         }
    //     }
    //     if (!$CTestCount) {
    //         for ($i = 1; $i <= $CDiffCount; $i++) {
    //             CDiff::where('frequency_id', admin()->company->frequency()->id)->where('sort', '=',  $i)->delete();
    //         }
    //     }
    // }


}
