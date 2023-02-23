<?php

namespace App\Observers;

use App\Models\ADiff;
use App\Models\ATest;
use App\Models\PDiff;
use App\Models\PTest;
use App\Models\ATestPTest;
use App\Models\CompanyCenterPoint;

class PDiffObserver
{
    public function creating(PDiff $model)
    {
        if (admin() && \admin()->type == 'admin') {


            $model->frequency_id = admin()->company->frequency()->id;
        }
    }

    // public function updated(PDiff $model)
    // {
    //     // P中心點
    //     $p_center_point = CompanyCenterPoint::where('category', 'p')->where('status', '1')->first()->numbering;


    //     $ATestCount = ATest::select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();
    //     $a_point_p = CompanyCenterPoint::where('category', 'p')->where('status', '1')->first()->a_point;

    //     $PTestCount = PTest::select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

    //     $p_point = CompanyCenterPoint::where('category', 'p')->where('status', '1')->first()->c_point;

    //     CompanyCenterPoint::updateOrCreate(['frequency_id' => admin()->company->frequency()->id, 'category' => 'p'], ['a_point' => $a_point_p, 'p_point' => $p_point, 'numbering' => $p_center_point]);

    //     //自動寫入
    //     $atest_all = ATest::where('frequency_id', admin()->company->frequency()->id)->pluck('sort', 'id')->toArray();


    //     foreach ($atest_all as $key => $value) {
    //         $atest_all[$key] = array(
    //             'frequency_id' => admin()->company->frequency()->id,
    //             'a_sort' => $value
    //         );
    //     }

    //     $ptests = PTest::where('frequency_id', admin()->company->frequency()->id)->get();

    //     foreach ($ptests as $ptest) {
    //         $ptest->a_tests()->sync($atest_all);
    //     }


    //     $ptest_all = PTest::where('frequency_id', admin()->company->frequency()->id)->pluck('sort', 'id')->toArray();
    //     foreach ($ptest_all as $key => $value) {
    //         $ptest_all[$key] = array(
    //             'frequency_id' => admin()->company->frequency()->id,
    //             'p_sort' => $value,
    //             'score' => 2
    //         );
    //     }

    //     $atests = ATest::where('frequency_id', admin()->company->frequency()->id)->get();


    //     foreach ($atests as  $value) {
    //         $value->p_tests()->sync($ptest_all);
    //     }

    //     $adiffs = ADiff::select('id', 'frequency_id', 'name', 'sort', 'numbering')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();


    //     $pdiffs = $model->select('id', 'frequency_id', 'name', 'sort', 'numbering')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

    //     $front_p = [];
    //     $back_p = [];
    //     $up_p = [];
    //     $down_p = [];
    //     foreach ($pdiffs as $key => $pdiff) {
    //         if ($p_point > $pdiff['sort']) {
    //             if ($key !== 0) {
    //                 array_push($up_p, $pdiff);
    //             }
    //         } else {
    //             if ($key !==  count($pdiffs) - 1) {
    //                 array_push($down_p, $pdiff);
    //             }
    //         }
    //     }

    //     $accumulator_p = [];
    //     $up_p_output = array_reverse($up_p);
    //     $new_up_p = array();
    //     $accumulator_p['numbering'] = $p_center_point;
    //     foreach ($up_p_output as $key => $value) {
    //         $accumulator_p['sort'] = $value['sort'] - 1;
    //         $accumulator_p['numbering'] -= $value['numbering'];
    //         array_push($new_up_p, $accumulator_p);
    //     }

    //     $accumulator_p = [];
    //     $down_p_output =  $down_p;
    //     $new_down_p = array();
    //     $accumulator_p['numbering'] = $p_center_point;
    //     foreach ($down_p_output as $key => $value) {
    //         $accumulator_p['sort'] = $value['sort'] + 1;
    //         $accumulator_p['numbering'] += $value['numbering'];
    //         array_push($new_down_p, $accumulator_p);
    //     }

    //     $p_center_point_array = array(['sort' => $p_point, 'numbering' => $p_center_point]);

    //     $p_array = array_merge_recursive(array_reverse($new_up_p), $p_center_point_array, $new_down_p);


    //     foreach ($adiffs as $key => $adiff) {
    //         if ($a_point_p > $adiff['sort']) {
    //             if ($key !== 0) {
    //                 array_push($front_p, $adiff);
    //             }
    //         } else {
    //             if ($key !==  count($adiffs) - 1) {
    //                 array_push($back_p, $adiff);
    //             }
    //         }
    //     }


    //     $atestptest = [];
    //     $atestptest_1 = [];
    //     foreach ($p_array as $index => $item) {
    //         $front_p_output = array_reverse($front_p);
    //         $new_front_p = array();
    //         $accumulator_p_front['numbering'] = $item['numbering'];
    //         foreach ($front_p_output as $key => $value) {
    //             $accumulator_p_front['a_sort'] = $value['sort'] - 1;
    //             $accumulator_p_front['p_sort'] = $item['sort'];
    //             $accumulator_p_front['numbering'] += $value['numbering'];
    //             array_push($new_front_p, $accumulator_p_front);
    //         }

    //         $back_p_output = $back_p;
    //         $new_back_p = array();
    //         $accumulator_p_back['numbering'] = $item['numbering'];
    //         foreach ($back_p_output as $key => $value) {
    //             $accumulator_p_back['a_sort'] = $value['sort'] + 1;
    //             $accumulator_p_back['p_sort'] = $item['sort'];
    //             $accumulator_p_back['numbering'] -= $value['numbering'];
    //             array_push($new_back_p, $accumulator_p_back);
    //         }

    //         $a_center_point_array = array(['a_sort' => $a_point_p, 'p_sort' => $item['sort'], 'numbering' => $item['numbering']]);

    //         $a_array = array_merge_recursive(array_reverse($new_front_p), $a_center_point_array, $new_back_p);

    //         if ($adiffs[0]['numbering'] != '') {
    //             $a_array[0]['numbering'] = $adiffs[0]['numbering'];
    //         }
    //         if ($adiffs[count($adiffs) - 1]['numbering'] != '') {
    //             $a_array[count($a_array) - 1]['numbering'] = $adiffs[count($adiffs) - 1]['numbering'];
    //         }

    //         if ($index == 0 && $pdiffs[0]['numbering'] != null) {
    //             foreach ($a_array as $akey => $avalue) {
    //                 $a_array[$akey]['numbering'] = $pdiffs[0]['numbering'];
    //             }
    //         }

    //         if ($index == count($p_array) - 1 && $pdiffs[count($pdiffs) - 1]['numbering'] != null) {
    //             foreach ($a_array as $akey => $avalue) {
    //                 $a_array[$akey]['numbering'] = $pdiffs[count($pdiffs) - 1]['numbering'];
    //             }
    //         }


    //         array_push($atestptest, $a_array);
    //         $atestptest_1 = array_merge($atestptest_1, $a_array);
    //     }

    //     $selects =  ATestPTest::select('id', 'frequency_id', 'a_sort', 'p_sort', 'score')->get()->toArray();

    //     foreach ($atestptest_1 as $key => $value) {
    //         if ($value['a_sort'] == $selects[$key]['a_sort'] && $value['p_sort'] == $selects[$key]['p_sort']) {
    //             $selects[$key]['score'] = $value['numbering'];
    //         }
    //     }

    //     app(ATestPTest::class)->updateBatch($selects);
    // }
}
