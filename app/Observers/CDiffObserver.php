<?php

namespace App\Observers;

use App\Models\ADiff;
use App\Models\ATest;
use App\Models\CDiff;
use App\Models\CTest;
use App\Models\ATestCTest;
use App\Models\CompanyCenterPoint;

class CDiffObserver
{
    public function creating(CDiff $model)
    {
        if (admin() && \admin()->type == 'admin') {

            $model->frequency_id = admin()->company->frequency()->id;
        }
    }

    // public function updated(CDiff $model)
    // {
    //     $center_point = CompanyCenterPoint::where('category', 'c')->where('status', '1')->first()->numbering;


    //     $ATestCount = ATest::select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();
    //     $a_point = CompanyCenterPoint::where('category', 'c')->where('status', '1')->first()->a_point;

    //     $CTestCount = CTest::select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

    //     $c_point = CompanyCenterPoint::where('category', 'c')->where('status', '1')->first()->c_point;

    //     CompanyCenterPoint::updateOrCreate(['frequency_id' => admin()->company->frequency()->id, 'category' => 'c'], ['a_point' => $a_point, 'c_point' => $c_point, 'numbering' => $center_point]);

    //     //自動寫入
    //     $atest_all = ATest::where('frequency_id', admin()->company->frequency()->id)->pluck('sort', 'id')->toArray();


    //     foreach ($atest_all as $key => $value) {
    //         $atest_all[$key] = array(
    //             'frequency_id' => admin()->company->frequency()->id,
    //             'a_sort' => $value
    //         );
    //     }

    //     $ctests = CTest::where('frequency_id', admin()->company->frequency()->id)->get();

    //     foreach ($ctests as $ctest) {
    //         $ctest->a_tests()->sync($atest_all);
    //     }


    //     $ctest_all = CTest::where('frequency_id', admin()->company->frequency()->id)->pluck('sort', 'id')->toArray();
    //     foreach ($ctest_all as $key => $value) {
    //         $ctest_all[$key] = array(
    //             'frequency_id' => admin()->company->frequency()->id,
    //             'c_sort' => $value,
    //             'score' => 2
    //         );
    //     }

    //     $atests = ATest::where('frequency_id', admin()->company->frequency()->id)->get();


    //     foreach ($atests as  $value) {
    //         $value->c_tests()->sync($ctest_all);
    //     }

    //     $adiffs = ADiff::select('id', 'frequency_id', 'name', 'sort', 'numbering')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();


    //     $cdiffs = $model->select('id', 'frequency_id', 'name', 'sort', 'numbering')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

    //     $front = [];
    //     $back = [];
    //     $up = [];
    //     $down = [];
    //     foreach ($cdiffs as $key => $cdiff) {
    //         if ($c_point > $cdiff['sort']) {
    //             if ($key !== 0) {
    //                 array_push($up, $cdiff);
    //             }
    //         } else {
    //             if ($key !==  count($cdiffs) - 1) {
    //                 array_push($down, $cdiff);
    //             }
    //         }
    //     }

    //     $accumulator = [];
    //     $up_output = array_reverse($up);
    //     $new_up = array();
    //     $accumulator['numbering'] = $center_point;
    //     foreach ($up_output as $key => $value) {
    //         $accumulator['sort'] = $value['sort'] - 1;
    //         $accumulator['numbering'] -= $value['numbering'];
    //         array_push($new_up, $accumulator);
    //     }

    //     $accumulator = [];
    //     $down_output = $down;
    //     $new_down = array();
    //     $accumulator['numbering'] = $center_point;
    //     foreach ($down_output as $key => $value) {
    //         $accumulator['sort'] = $value['sort'] + 1;
    //         $accumulator['numbering'] += $value['numbering'];
    //         array_push($new_down, $accumulator);
    //     }

    //     $c_center_point_array = array(['sort' => $c_point, 'numbering' => $center_point]);

    //     $c_array = array_merge_recursive(array_reverse($new_up), $c_center_point_array, $new_down);


    //     foreach ($adiffs as $key => $adiff) {
    //         if ($a_point > $adiff['sort']) {
    //             if ($key !== 0) {
    //                 array_push($front, $adiff);
    //             }
    //         } else {
    //             if ($key !==  count($adiffs) - 1) {
    //                 array_push($back, $adiff);
    //             }
    //         }
    //     }

    //     $atestctest = [];
    //     $atestctest_1 = [];
    //     foreach ($c_array as $index => $item) {
    //         $front_output = array_reverse($front);
    //         $new_front = array();
    //         $accumulator_front['numbering'] = $item['numbering'];
    //         foreach ($front_output as $key => $value) {
    //             $accumulator_front['a_sort'] = $value['sort'] - 1;
    //             $accumulator_front['c_sort'] = $item['sort'];
    //             $accumulator_front['numbering'] += $value['numbering'];
    //             array_push($new_front, $accumulator_front);
    //         }

    //         $back_output = $back;
    //         $new_back = array();
    //         $accumulator_back['numbering'] = $item['numbering'];
    //         foreach ($back_output as $key => $value) {
    //             $accumulator_back['a_sort'] = $value['sort'] + 1;
    //             $accumulator_back['c_sort'] = $item['sort'];
    //             $accumulator_back['numbering'] -= $value['numbering'];
    //             array_push($new_back, $accumulator_back);
    //         }

    //         $a_center_point_array = array(['a_sort' => $a_point, 'c_sort' => $item['sort'], 'numbering' => $item['numbering']]);

    //         $a_array = array_merge_recursive(array_reverse($new_front), $a_center_point_array, $new_back);

    //         if ($adiffs[0]['numbering'] != '') {
    //             $a_array[0]['numbering'] = $adiffs[0]['numbering'];
    //         }
    //         if ($adiffs[count($adiffs) - 1]['numbering'] != '') {
    //             $a_array[count($a_array) - 1]['numbering'] = $adiffs[count($adiffs) - 1]['numbering'];
    //         }

    //         if ($index == 0 && $cdiffs[0]['numbering'] != null) {
    //             foreach ($a_array as $akey => $avalue) {
    //                 $a_array[$akey]['numbering'] = $cdiffs[0]['numbering'];
    //             }
    //         }

    //         if ($index == count($c_array) - 1 && $cdiffs[count($cdiffs) - 1]['numbering'] != null) {
    //             foreach ($a_array as $akey => $avalue) {
    //                 $a_array[$akey]['numbering'] = $cdiffs[count($cdiffs) - 1]['numbering'];
    //             }
    //         }


    //         array_push($atestctest, $a_array);
    //         $atestctest_1 = array_merge($atestctest_1, $a_array);
    //     }

    //     $selects =  ATestCTest::select('id', 'frequency_id', 'a_sort', 'c_sort', 'score')->get()->toArray();

    //     foreach ($atestctest_1 as $key => $value) {
    //         if ($value['a_sort'] == $selects[$key]['a_sort'] && $value['c_sort'] == $selects[$key]['c_sort']) {
    //             $selects[$key]['score'] = $value['numbering'];
    //         }
    //     }

    //     app(ATestCTest::class)->updateBatch($selects);
    // }
}
