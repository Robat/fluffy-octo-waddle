<?php

namespace App\Http\Controllers\Admin;

use App\Models\ADiff;
use App\Models\ATest;
use App\Models\CDiff;
use App\Models\CTest;
use App\Models\Grade;
use App\Models\PDiff;
use App\Models\PTest;
use App\Classes\Reply;
use App\Models\CValue;
use App\Models\PValue;
use App\Models\ATestCDiff;
use App\Models\ATestCTest;
use App\Models\ATestPDiff;
use App\Models\ATestPTest;
use App\Models\CompanyRank;
use App\Models\CompanyADiff;
use App\Models\CompanyATest;
use App\Models\CompanyCDiff;
use App\Models\CompanyCTest;
use App\Models\CompanyDDiff;
use App\Models\CompanyDTest;
use App\Models\CompanyPDiff;
use App\Models\CompanyPTest;
use App\Models\CompanyCValue;
use App\Models\CompanyPValue;
use App\Models\EmployeeScore;
use App\Models\EmployeeSalary;
use App\Models\CompanyCenterPoint;
use App\Models\CompanyBonusSetting;
use App\Models\CompanySpecialBonus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Models\CompanyEvaluatingBonus;
use App\Models\CompanyGuaranteedBonus;
use App\Models\CompanyManagementBonus;
use App\Models\CompanyBonusCalculation;
use App\Models\CompanyPerformanceBonus;
use Illuminate\Support\Facades\Request;
use App\Models\CompanyATestCompanyCTest;
use App\Models\CompanyDTestCompanyPTest;
use App\Models\CompanySimpleMonthMethod;
use App\Models\CompanyTotalAmountMethod;
use Yajra\DataTables\Facades\DataTables;
use App\Models\CompanyComplexBonusMethod;
use App\Models\CompanySpecialBonusDetail;
use App\Models\CompanyEvaluatingBonusList;
use App\Models\CompanyPerformanceBonusList;
use App\Models\CompanyManagementBonusDetail;
use App\Http\Controllers\AdminBaseController;
use App\Models\CompanyComplexBonusMethodDetail;
use App\Http\Requests\Admin\CompanyCValue\UpdateRequest;

class CompanyBonusController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->salariesOpen = 'active open';
        $this->pageTitle = '獎金設定(模擬)列表';
        $this->totalAmountActive = 'active';
    }


    public function index()
    {
        return View::make('admin.bonus_settings.index', $this->data);
    }

    public function ajax_index()
    {
        $result = CompanyBonusSetting::select(['id', 'bonus_frequency', 'created_at', 'updated_at']);


        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-edit"></i> 編輯</a>
                <a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="editName(' . $row->id . ')"><i class="fa fa-edit"></i> 修改名稱</a>

              						<a href="javascript:void(0)" class="btn btn-danger" onclick="del(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-trash"></i> 刪除</a>';
            })
            ->escapeColumns(['action'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return View::make('admin.bonus_settings.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\Illuminate\Http\Request $request)
    {
        CompanyBonusSetting::create($request->toArray());

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }


    public function edit_name($id)
    {

        $this->bonus_setting = CompanyBonusSetting::find($id);

        return View::make('admin.bonus_settings.edit_name', $this->data);
    }

    public function update_name(\Illuminate\Http\Request $request, $id)
    {

        $bonus_setting = CompanyBonusSetting::findOrFail($id);
        // $data = $request->all();
        $bonus_setting->update($request->toArray());
        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyFrequency  $companyFrequency
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     $calculationId = CompanyBonusCalculation::where('bonus_setting_id', $id)->first()->id;

    //     //員工薪水
    //     $all_salaries = EmployeeSalary::where('frequency_id', admin()->company->frequency()->id)->pluck('salary')->toArray(0);


    //     $this->all_salaries = array_sum($all_salaries);

    //     $this->setting = CompanyBonusSetting::find($id);

    //     $this->ranks = CompanyRank::where('frequency_id', admin()->company->frequency()->id)->pluck('name', 'id');

    //     $this->rank = CompanyRank::where('frequency_id', admin()->company->frequency()->id)->where('default', 'yes')->first()->pluck('name', 'id');

    //     $this->company_total_amount_methods = CompanyTotalAmountMethod::where('frequency_id', admin()->company->frequency()->id)->first();

    //     $this->company_simple_month_methods = CompanySimpleMonthMethod::where('frequency_id', admin()->company->frequency()->id)->first();

    //     $company_complex_bonus_method = CompanyComplexBonusMethod::with(['details.department', 'details.department.members', 'details.department.members.employee_salaries'])->where('frequency_id', admin()->company->frequency()->id)->first();

    //     $this->complex_bonuses = $company_complex_bonus_method->details;


    //     $this->company_guaranteed_bonus = CompanyGuaranteedBonus::where('frequency_id', admin()->company->frequency()->id)->first();



    //     $company_special_bonus = CompanySpecialBonus::with(['details.department', 'details.department.members', 'details.department.members.employee_salaries'])->where('frequency_id', admin()->company->frequency()->id)->first();

    //     $this->special_bonuses = $company_special_bonus->details;


    //     $company_management_bonus = CompanyManagementBonus::with(['details.designation', 'details.designation.members', 'details.designation.members.employee_salaries'])->first();

    //     $this->management_bonuses = $company_management_bonus->details;


    //     $this->company_guaranteed_bonus = CompanyGuaranteedBonus::where('frequency_id', admin()->company->frequency()->id)->first();


    //     // 績效 -> 轉換成模擬
    //     $this->atests = CompanyATest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

    //     $this->dtests = CompanyDTest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

    //     $this->a_performances = CompanyPerformanceBonus::where('calculation_id', $calculationId)->get();

    //     $this->a_evaluatings = CompanyEvaluatingBonus::where('calculation_id', $calculationId)->get();
    //     // $this->atests = CompanyPerformanceBonusList::where('bonus_setting_id', $id)->first()->performances;

    //     // $this->evaluating_atests =  CompanyEvaluatingBonusList::where('bonus_setting_id', $id)->first()->evaluates;


    //     // 績效差距
    //     $this->adiffs = CompanyADiff::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();


    //     $this->ddiffs = CompanyDDiff::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

    //     // CR差距
    //     $this->cdiffs = CompanyCDiff::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

    //     $this->cvalues = CompanyCValue::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

    //     $this->ctests = CompanyCTest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

    //     $this->actest = CompanyATestCompanyCTest::all();

    //     //p
    //     $this->pdiffs = CompanyPDiff::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

    //     $this->pvalues = CompanyPValue::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

    //     $this->ptests = CompanyPTest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

    //     $this->aptest = CompanyDTestCompanyPTest::all();

    //     $this->center_point = CompanyCenterPoint::where('frequency_id', admin()->company->frequency()->id);

    //     $this->p_center_point = CompanyCenterPoint::where('frequency_id', admin()->company->frequency()->id)->where('category', 'p');

    //     return View::make('admin.bonus_settings.edit', $this->data);
    // }


    public function edit($id)
    {
        $calculationId = CompanyBonusCalculation::where('bonus_setting_id', $id)->first()->id;

        //員工薪水
        $all_salaries = EmployeeSalary::where('frequency_id', admin()->company->frequency()->id)->pluck('salary')->toArray(0);

        $this->all_salaries = array_sum($all_salaries);
        $this->setting = CompanyBonusSetting::with('rank')->find($id);
        $this->ranks = CompanyRank::where('frequency_id', admin()->company->frequency()->id)->pluck('name', 'id');
        $this->rank = CompanyRank::where('frequency_id', admin()->company->frequency()->id)->where('default', 'yes')->first()->pluck('name', 'id');
        $this->company_total_amount_methods = CompanyTotalAmountMethod::where('frequency_id', admin()->company->frequency()->id)->first();
        $this->company_simple_month_methods = CompanySimpleMonthMethod::where('frequency_id', admin()->company->frequency()->id)->first();

        $company_complex_bonus_method = CompanyComplexBonusMethod::with(['details.department', 'details.department.members.employee_salaries'])->where('frequency_id', admin()->company->frequency()->id)->first();
        $this->complex_bonuses = $company_complex_bonus_method->details;

        $this->company_guaranteed_bonus = CompanyGuaranteedBonus::where('frequency_id', admin()->company->frequency()->id)->first();

        $company_special_bonus = CompanySpecialBonus::with(['details.department', 'details.department.members.employee_salaries'])->where('frequency_id', admin()->company->frequency()->id)->first();
        $this->special_bonuses = $company_special_bonus->details;

        $company_management_bonus = CompanyManagementBonus::with(['details.designation', 'details.designation.members.employee_salaries'])->first();
        $this->management_bonuses = $company_management_bonus->details;

        $this->company_guaranteed_bonus = CompanyGuaranteedBonus::where('frequency_id', admin()->company->frequency()->id)->first();

        // 績效 -> 轉換成模擬
        // $this->atests = CompanyATest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();
        // $this->dtests = CompanyDTest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();
        // $this->a_performances = CompanyPerformanceBonus::where('calculation_id', $calculationId)->get();
        // $this->a_evaluatings = CompanyEvaluatingBonus::where('calculation_id', $calculationId)->get();

        $calculation = CompanyBonusCalculation::with([
            'aTests', 'dTests', 'performanceBonuses', 'evaluatingBonuses'
        ])
            ->findOrFail($calculationId);

        $this->atests = $calculation->aTests;
        $this->dtests = $calculation->dTests;
        $this->a_performances = $calculation->performanceBonuses;
        $this->a_evaluatings = $calculation->evaluatingBonuses;

        // 績效差距
        $this->adiffs = CompanyADiff::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();


        $this->ddiffs = CompanyDDiff::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

        // CR差距
        $this->cdiffs = CompanyCDiff::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

        $this->cvalues = CompanyCValue::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

        $this->ctests = CompanyCTest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

        $this->actest = CompanyATestCompanyCTest::all();

        //p
        $this->pdiffs = CompanyPDiff::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

        $this->pvalues = CompanyPValue::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

        $this->ptests = CompanyPTest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

        $this->aptest = CompanyDTestCompanyPTest::all();

        $this->center_point = CompanyCenterPoint::where('frequency_id', admin()->company->frequency()->id);

        $this->p_center_point = CompanyCenterPoint::where('frequency_id', admin()->company->frequency()->id)->where('category', 'p');

        return view('admin.bonus_settings.edit', $this->data)
            ->with('atests', $this->atests)
            ->with('dtests', $this->dtests)
            ->with('a_performances', $this->a_performances)
            ->with('a_evaluatings', $this->a_evaluatings)
            ->with('adiffs', $this->adiffs)
            ->with('ddiffs', $this->ddiffs)
            ->with('cdiffs', $this->cdiffs)
            ->with('cvalues', $this->cvalues)
            ->with('ctests', $this->ctests)
            ->with('actest', $this->actest)
            ->with('pdiffs', $this->pdiffs)
            ->with('pvalues', $this->pvalues)
            ->with('ptests', $this->ptests)
            ->with('aptest', $this->aptest)
            ->with('center_point', $this->center_point)
            ->with('p_center_point', $this->p_center_point)
            ->with('all_salaries', $this->all_salaries)
            ->with('setting', $this->setting)
            ->with('ranks', $this->ranks)
            ->with('rank', $this->rank)
            ->with('company_total_amount_methods', $this->company_total_amount_methods)
            ->with('company_simple_month_methods', $this->company_simple_month_methods)
            ->with('complex_bonuses', $this->complex_bonuses)
            ->with('special_bonuses', $this->special_bonuses)
            ->with('management_bonuses', $this->management_bonuses)
            ->with('company_guaranteed_bonus', $this->company_guaranteed_bonus);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyFrequency  $companyFrequency
     * @return \Illuminate\Http\Response
     */
    public function update(\Illuminate\Http\Request $request, $id)
    {
        $calculationId = CompanyBonusCalculation::where('bonus_setting_id', $id)->first()->id;
        // 獎金預算設定-複雜月數制
        $complexes = CompanyComplexBonusMethodDetail::where('frequency_id', admin()->company->frequency()->id)->pluck('department_id', 'id')->toArray();

        $complexes_new_data = [];

        foreach ($complexes as $key => $value) {
            array_push($complexes_new_data, array(
                "id" => $key,
                "number" => $request->complex_bonus['number'][$key],
                "numbering" => $request->complex_bonus['numbering'][$key],
                "subtotal" => $request->complex_bonus['subtotal'][$key],
            ));
        }

        app(CompanyComplexBonusMethodDetail::class)->updateBatch($complexes_new_data);

        // 事業部特別獎金
        $specials = CompanySpecialBonusDetail::where('frequency_id', admin()->company->frequency()->id)->pluck('department_id', 'id')->toArray();

        $special_new_data = [];

        foreach ($specials as $key => $value) {
            array_push($special_new_data, array(
                "id" => $key,
                "number" => $request->special['number'][$key],
                "fixed_amount" => $request->special['fixed'][$key],
                "subtotal" => $request->special['subtotal'][$key],

            ));
        }

        app(CompanySpecialBonusDetail::class)->updateBatch($special_new_data);

        // 管理職責任獎金
        $managements = CompanyManagementBonusDetail::where('frequency_id', admin()->company->frequency()->id)->pluck('designation_id', 'id')->toArray();



        $management_new_data = [];

        foreach ($managements as $key => $value) {
            array_push($management_new_data, array(
                "id" => $key,
                "number" => $request->management['number'][$key],
                "fixed_amount" => $request->management['fixed'][$key],
                "subtotal" => $request->management['subtotal'][$key],
            ));
        }
        $employee_scores = EmployeeScore::with('employee', 'employee.designation')->get();
        $man_new_data = [];


        foreach ($employee_scores as $employee_score) {

            $man = CompanyManagementBonusDetail::where('frequency_id', admin()->company->frequency()->id)->where('designation_id', $employee_score->employee->designation->id)->first();
            $employee_s = EmployeeScore::findOrFail($employee_score->id);
            $employee_s->bonus_management = $man->fixed_amount;
            $employee_s->save();

            // array_push($man_new_data, $man->fixed_amount);
        }
        // dd($employee_score->employee->designation->id);
        // dd($managements);

        app(CompanyManagementBonusDetail::class)->updateBatch($management_new_data);


        //寫入 list

        //獎金預算設定- 總金額制
        $totalAmount = CompanyTotalAmountMethod::where('frequency_id', admin()->company->frequency()->id);
        $totalAmount->update([
            'total_amount' => $request->company_total_amount_methods
        ]);
        //獎金預算設定- 簡單月數制
        $simpleMonth = CompanySimpleMonthMethod::where('frequency_id', admin()->company->frequency()->id);
        $simpleMonth->update([
            'simple_month' => $request->company_simple_month_methods
        ]);


        $guaranteed = CompanyGuaranteedBonus::where('frequency_id', admin()->company->frequency()->id);
        $guaranteed->update([
            'fixed_amount' => $request->company_guaranteed_bonus
        ]);

        // CR的中間值位置
        $location_c = explode("_", $request->location_c);
        $center_point =  CompanyCenterPoint::findOrFail($location_c[0]);

        // dd($location_c);

        $center_point->c_point = $location_c[1];
        $center_point->a_point = $location_c[2];
        $center_point->numbering = $request->center_point_numbering;
        $center_point->save();

        $location_p = explode("_", $request->location_p);
        $p_center_point =  CompanyCenterPoint::findOrFail($location_p[0]);


        $p_center_point->c_point = $location_p[1];
        $p_center_point->a_point = $location_p[2];
        $p_center_point->numbering = $request->p_center_point_numbering;
        $p_center_point->save();


        $setting = CompanyBonusSetting::findOrFail($id);
        $setting->bon_totalAmount = $request->company_total_amount_methods;
        $setting->bon_simpleMonth = $request->company_simple_month_methods;
        $setting->salaryScale_id = $request->salaryScale_id;
        $setting->personex = $request->personex;
        $setting->bonusex = $request->bonusex;

        $bonus_guaranteed = ($request->bonus_guaranteed == 'yes') ? 1 : 0;
        $setting->bonus_guaranteed = $bonus_guaranteed;

        $bonus_special = ($request->bonus_special == 'yes') ? 1 : 0;
        $setting->bonus_special = $bonus_special;

        $bonus_man = ($request->bonus_man == 'yes') ? 1 : 0;
        $setting->bonus_man = $bonus_man;

        $setting->all_numbering = $request->all_numbering;
        $setting->save();

        // 績效結果法 儲存個別模擬
        foreach ($request->performance as $key => $value) {
            // $atest = ATest::findOrFail($key);
            $a_performance = CompanyPerformanceBonus::findOrFail($key);
            // $atest = CompanyPerformanceBonus::findOrFail($key);
            $a_performance->numbering = $value;
            $a_performance->save();
        }

        //$this->a_evaluatings = CompanyPerformanceBonus::where('calculation_id', $calculationId)->get();



        foreach ($request->evaluating as $key => $value) {
            $a_evaluating = CompanyEvaluatingBonus::findOrFail($key);
            $a_evaluating->pay = $value;
            $a_evaluating->save();
        }

        foreach ($request->adiff as $key => $value) {
            $adiff = CompanyADiff::findOrFail($key);
            $adiff->numbering = $value;
            $adiff->save();
        }

        foreach ($request->ddiff as $key => $value) {
            $ddiff = CompanyDDiff::findOrFail($key);
            $ddiff->numbering = $value;
            $ddiff->save();
        }

        foreach ($request->cvalue as $key => $value) {
            $cvalue = CompanyCValue::findOrFail($key);
            $cvalue->numbering = $value;
            $cvalue->save();
        }

        foreach ($request->cdiff as $key => $value) {
            $cdiff = CompanyCDiff::findOrFail($key);
            $cdiff->numbering = $value;
            $cdiff->save();
        }

        //p
        foreach ($request->pvalue as $key => $value) {
            $pvalue = CompanyPValue::findOrFail($key);
            $pvalue->numbering = $value;
            $pvalue->save();
        }

        foreach ($request->pdiff as $key => $value) {
            $pdiff = CompanyPDiff::findOrFail($key);
            $pdiff->numbering = $value;
            $pdiff->save();
        }


        // start
        // 新增預設中心點 2.5
        $center_point = $request->center_point_numbering;


        $ATestCount = CompanyATest::select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();
        $a_point = $location_c[2];

        $CTestCount = CompanyCTest::select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

        $c_point = $location_c[1];

        CompanyCenterPoint::updateOrCreate(['frequency_id' => admin()->company->frequency()->id, 'category' => 'c'], ['a_point' => $a_point, 'c_point' => $c_point, 'numbering' => $center_point]);

        //自動寫入
        $atest_all = CompanyATest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->pluck('sort', 'id')->toArray();


        foreach ($atest_all as $key => $value) {
            $atest_all[$key] = array(
                'frequency_id' => admin()->company->frequency()->id,
                'a_sort' => $value,
                'bonus_calculation_id' => $calculationId
            );
        }

        $ctests = CompanyCTest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

        foreach ($ctests as $ctest) {
            $ctest->company_atests()->sync($atest_all);
        }


        $ctest_all = CompanyCTest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->pluck('sort', 'id')->toArray();
        foreach ($ctest_all as $key => $value) {
            $ctest_all[$key] = array(
                'frequency_id' => admin()->company->frequency()->id,
                'c_sort' => $value,
                'score' => 2,
                'bonus_calculation_id' => $calculationId
            );
        }

        $atests = CompanyATest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();


        foreach ($atests as  $value) {
            $value->company_ctests()->sync($ctest_all);
        }

        $adiffs = CompanyADiff::select('id', 'frequency_id', 'name', 'sort', 'numbering')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->where('bonus_calculation_id', $calculationId)->orderBy('sort', 'ASC')->get()->toArray();


        $cdiffs = CompanyCDiff::select('id', 'frequency_id', 'bonus_calculation_id', 'name', 'sort', 'numbering')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->where('bonus_calculation_id', $calculationId)->orderBy('sort', 'ASC')->get()->toArray();



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

        //
        $c_array = array_merge_recursive(array_reverse($new_up), $c_center_point_array, $new_down);

        // dd($c_array);

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
        // dd($a_point);

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

            // dd($a_array);




            if ($index == 0 && $cdiffs[0]['numbering'] != null) {
                foreach ($a_array as $akey => $avalue) {
                    $a_array[$akey]['numbering'] = $cdiffs[0]['numbering'];
                }
            }

            if ($index == 0 && $cdiffs[0]['numbering'] === 0.0) {
                foreach ($a_array as $akey => $avalue) {
                    $a_array[$akey]['numbering'] = 0;
                }
            }

            if ($index == count($c_array) - 1 && $cdiffs[count($cdiffs) - 1]['numbering'] != null) {
                foreach ($a_array as $akey => $avalue) {
                    $a_array[$akey]['numbering'] = $cdiffs[count($cdiffs) - 1]['numbering'];
                }
            }

            if ($index == count($c_array) - 1 && $cdiffs[count($cdiffs) - 1]['numbering'] === 0.0) {
                foreach ($a_array as $akey => $avalue) {
                    $a_array[$akey]['numbering'] = 0;
                }
            }

            if ($adiffs[0]['numbering'] != null) {
                $a_array[0]['numbering'] = $adiffs[0]['numbering'];
            }

            if ($adiffs[0]['numbering'] === 0) {
                $a_array[0]['numbering'] = 0;
            }

            // dd($adiffs[count($adiffs) - 1]['numbering']);
            if ($adiffs[count($adiffs) - 1]['numbering'] != null) {
                $a_array[count($a_array) - 1]['numbering'] = $adiffs[count($adiffs) - 1]['numbering'];
            }

            if ($adiffs[count($adiffs) - 1]['numbering'] === 0.0) {
                $a_array[count($a_array) - 1]['numbering'] = 0;
            }



            array_push($atestctest, $a_array);

            $atestctest_1 = array_merge($atestctest_1, $a_array);
        }

        // dd($atestctest_1);

        $selects =  CompanyATestCompanyCTest::select('id', 'frequency_id', 'company_a_test_id', 'company_c_test_id', 'a_sort', 'c_sort', 'score')->get()->toArray();  //資料減少

        // dd($selects);
        foreach ($atestctest_1 as $key => $value) {
            // if ($value['a_sort'] == $selects[$key]['a_sort'] && $value['c_sort'] == $selects[$key]['c_sort']) {
            //     $selects[$key]['score'] = $value['numbering'];
            // }
            // 1213改用 foreach where 比對寫入 數量應該不會超過100

            $sort_c = CompanyATestCompanyCTest::where('a_sort', $value['a_sort'])->where('c_sort', $value['c_sort'])->where('bonus_calculation_id', $calculationId)->first();

            $sort_c->score = $value['numbering'];
            $sort_c->save();
        }


        // dd($atestctest_1);
        //1213放棄使用批次修改
        // app(ATestCTest::class)->updateBatch($selects);


        /****************** p ********************/

        // P中心點
        $p_center_point = $request->p_center_point_numbering;


        $DTestCount = CompanyDTest::select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->where('bonus_calculation_id', $calculationId)->orderBy('sort', 'ASC')->get()->toArray();
        $a_point_p = $location_p[2];

        $PTestCount = CompanyPTest::select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

        $p_point = $location_p[1];

        CompanyCenterPoint::updateOrCreate(['frequency_id' => admin()->company->frequency()->id, 'category' => 'p'], ['a_point' => $a_point_p, 'p_point' => $p_point, 'numbering' => $p_center_point]);

        //自動寫入
        $dtest_all = CompanyDTest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->pluck('sort', 'id')->toArray();

        // dd($atest_all);
        foreach ($dtest_all as $key => $value) {
            $dtest_all[$key] = array(
                'frequency_id' => admin()->company->frequency()->id,
                'd_sort' => $value,
                'bonus_calculation_id' => $calculationId
            );
        }

        $ptests = CompanyPTest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();

        foreach ($ptests as $ptest) {
            $ptest->company_dtests()->sync($dtest_all);
        }


        $ptest_all = CompanyPTest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->pluck('sort', 'id')->toArray();

        foreach ($ptest_all as $key => $value) {
            $ptest_all[$key] = array(
                'frequency_id' => admin()->company->frequency()->id,
                'p_sort' => $value,
                'score' => 2,
                'bonus_calculation_id' => $calculationId
            );
        }


        $dtests = CompanyDTest::where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->get();


        foreach ($dtests as  $value) {
            $value->company_ptests()->sync($ptest_all);
        }

        $ddiffs = CompanyDDiff::select('id', 'frequency_id', 'bonus_calculation_id', 'name', 'sort', 'numbering')->where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();


        $pdiffs = CompanyPDiff::select('id', 'frequency_id', 'bonus_calculation_id', 'name', 'sort', 'numbering')->where('frequency_id', admin()->company->frequency()->id)->where('bonus_calculation_id', $calculationId)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();

        $front_p = [];
        $back_p = [];
        $up_p = [];
        $down_p = [];
        foreach ($pdiffs as $key => $pdiff) {
            if ($p_point > $pdiff['sort']) {
                if ($key !== 0) {
                    array_push($up_p, $pdiff);
                }
            } else {
                if ($key !==  count($pdiffs) - 1) {
                    array_push($down_p, $pdiff);
                }
            }
        }

        $accumulator_p = [];
        $up_p_output = array_reverse($up_p);
        $new_up_p = array();
        $accumulator_p['numbering'] = $p_center_point;
        foreach ($up_p_output as $key => $value) {
            $accumulator_p['sort'] = $value['sort'] - 1;
            $accumulator_p['numbering'] -= $value['numbering'];
            array_push($new_up_p, $accumulator_p);
        }

        $accumulator_p = [];
        $down_p_output =  $down_p;
        $new_down_p = array();
        $accumulator_p['numbering'] = $p_center_point;
        foreach ($down_p_output as $key => $value) {
            $accumulator_p['sort'] = $value['sort'] + 1;
            $accumulator_p['numbering'] += $value['numbering'];
            array_push($new_down_p, $accumulator_p);
        }

        $p_center_point_array = array(['sort' => $p_point, 'numbering' => $p_center_point]);

        $p_array = array_merge_recursive(array_reverse($new_up_p), $p_center_point_array, $new_down_p);


        foreach ($ddiffs as $key => $ddiff) {
            if ($a_point_p > $ddiff['sort']) {
                if ($key !== 0) {
                    array_push($front_p, $ddiff);
                }
            } else {
                if ($key !==  count($ddiffs) - 1) {
                    array_push($back_p, $ddiff);
                }
            }
        }


        $dtestptest = [];
        $dtestptest_1 = [];
        foreach ($p_array as $index => $item) {
            $front_p_output = array_reverse($front_p);
            $new_front_p = array();
            $accumulator_p_front['numbering'] = $item['numbering'];
            foreach ($front_p_output as $key => $value) {
                $accumulator_p_front['d_sort'] = $value['sort'] - 1;
                $accumulator_p_front['p_sort'] = $item['sort'];
                $accumulator_p_front['numbering'] += $value['numbering'];
                array_push($new_front_p, $accumulator_p_front);
            }

            $back_p_output = $back_p;
            $new_back_p = array();
            $accumulator_p_back['numbering'] = $item['numbering'];
            foreach ($back_p_output as $key => $value) {
                $accumulator_p_back['d_sort'] = $value['sort'] + 1;
                $accumulator_p_back['p_sort'] = $item['sort'];
                $accumulator_p_back['numbering'] -= $value['numbering'];
                array_push($new_back_p, $accumulator_p_back);
            }

            $a_center_point_array = array(['d_sort' => $a_point_p, 'p_sort' => $item['sort'], 'numbering' => $item['numbering']]);

            $d_array = array_merge_recursive(array_reverse($new_front_p), $a_center_point_array, $new_back_p);


            if ($index == 0 && $pdiffs[0]['numbering'] != null) {
                foreach ($d_array as $dkey => $dvalue) {
                    $d_array[$dkey]['numbering'] = $pdiffs[0]['numbering'];
                }
            }

            if ($index == 0 && $pdiffs[0]['numbering'] === 0.0) {
                foreach ($d_array as $dkey => $dvalue) {
                    $d_array[$dkey]['numbering'] = 0;
                }
            }

            if ($index == count($p_array) - 1 && $pdiffs[count($pdiffs) - 1]['numbering'] != null) {
                foreach ($d_array as $dkey => $dvalue) {
                    $d_array[$dkey]['numbering'] = $pdiffs[count($pdiffs) - 1]['numbering'];
                }
            }


            if ($index == count($p_array) - 1 && $pdiffs[count($pdiffs) - 1]['numbering'] === 0.0) {
                foreach ($d_array as $dkey => $dvalue) {
                    $d_array[$dkey]['numbering'] = 0;
                }
            }

            if ($ddiffs[0]['numbering'] != null) {
                $d_array[0]['numbering'] = $ddiffs[0]['numbering'];
            }

            if ($ddiffs[0]['numbering'] === 0) {
                $d_array[0]['numbering'] = 0;
            }

            if ($ddiffs[count($ddiffs) - 1]['numbering'] != null) {
                $d_array[count($d_array) - 1]['numbering'] = $ddiffs[count($ddiffs) - 1]['numbering'];
            }

            if ($ddiffs[count($ddiffs) - 1]['numbering'] === 0.0) {
                $d_array[count($d_array) - 1]['numbering'] = 0;
            }

            array_push($dtestptest, $d_array);
            $dtestptest_1 = array_merge($dtestptest_1, $d_array);
        }
        // dd($dtestptest_1);

        $selects =  CompanyDTestCompanyPTest::select('id', 'frequency_id', 'd_sort', 'p_sort', 'score', 'bonus_calculation_id')->where('bonus_calculation_id', $calculationId)->get()->toArray();

        // dd($dtestptest_1);
        foreach ($dtestptest_1 as $key => $value) {
            // if ($value['d_sort'] == $selects[$key]['d_sort'] && $value['p_sort'] == $selects[$key]['p_sort']) {
            //     $selects[$key]['score'] = $value['numbering'];
            // }
            // 1213改用 foreach where 比對寫入 數量應該不會超過100

            $sort_p = CompanyDTestCompanyPTest::where('bonus_calculation_id', $calculationId)->where('d_sort', $value['d_sort'])->where('p_sort', $value['p_sort'])->first();

            $sort_p->score = $value['numbering'];

            $sort_p->save();
        }


        //1213放棄使用批次修改
        // app(ATestPTest::class)->updateBatch($selects);


        // 員工績效結果 更新

        $employeeScores = EmployeeScore::with(['employee', 'employee.employee_salaries', 'employee.designation', 'employee.department', 'employee.designation.grade', 'company_bonus'])->get();

        // foreach ($employeeScores as $employee_score) {
        //     $employee_salary = array_sum($employee_score->employee->employee_salaries->pluck('salary')->toArray());

        //     $designation = $employee_score->employee->designation->id;


        //     $management = CompanyManagementBonusDetail::where('frequency_id', admin()->company->frequency()->id)->where('designation_id', $designation)->first();

        //     $guaranteed = CompanyGuaranteedBonus::where('frequency_id', admin()->company->frequency()->id)->first();
        //     $department = $employee_score->employee->department->id;
        //     $special = CompanySpecialBonusDetail::where('frequency_id', admin()->company->frequency()->id)->where('department_id', $department)->first();

        //     // ******* //
        //     // CR
        //     $cvalues = CompanyCValue::where('bonus_calculation_id', $calculationId)->pluck('mark')->toArray();
        //     // $c_center = CompanyCValue::where('center', '1')->where('bonus_calculation_id', $calculationId)->first()->mark;
        //     $grade = Grade::where('grade', $employee_score->employee->designation->grade->grade)->pluck('id')->first();

        //     $salaries = $employee_score->employee->employee_salaries->pluck('salary')->toArray();
        //     dd($employee_score);
        //     $max = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_max;

        //     $mid = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_mid;
        //     $min = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_min;

        //     // dd($max . "-" . $mid . "-" . $min);

        //     $c_salary_array = [];

        //     foreach ($cvalues as $key => $value) {
        //         $c_salary =  round($value * $mid);
        //         array_push($c_salary_array, $c_salary);
        //     }

        //     // dd($c_salary_array);

        //     array_push($c_salary_array, 0);
        //     $c_salary_array = array_reverse($c_salary_array);

        //     //目前綁定的績效ID 值


        //     $atestId = $employee_score->score_id;



        //     $ctest = CompanyCTest::orderBy('sort', 'DESC')->where('bonus_calculation_id', $calculationId)->pluck('id');

        //     //由小到大排列 0,49600, 55800,68200, 74400
        //     foreach ($c_salary_array as $key => $value) {
        //         if (array_sum($salaries) >= $value) {
        //             $ctestId = $ctest[$key];
        //         }
        //     }


        //     if (CompanyATestCompanyCTest::where('company_a_test_id', $atestId)->where('company_c_test_id', $ctestId)->first()) {
        //         $c_numbering = CompanyATestCompanyCTest::where('company_a_test_id', $atestId)->where('company_c_test_id', $ctestId)->first()->score;
        //     } else {
        //         $c_numbering = 0;
        //     }

        //     // dd($c_numbering);
        //     // c的獎金月數

        //     // dd($c_numbering);
        //     // *******//
        //     $pvalues = CompanyPValue::where('bonus_calculation_id', $calculationId)->pluck('mark')->toArray();
        //     // $p_center = CompanyPValue::where('bonus_calculation_id', $calculationId)->where('center', '1')->first()->mark;
        //     $grade = Grade::where('grade', $employee_score->employee->designation->grade->grade)->pluck('id')->first();
        //     $max = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_max;
        //     $mid = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_mid;
        //     $min = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_min;

        //     $arrayMin = min($pvalues);
        //     $arrayMax = max($pvalues);
        //     $arrayCenterValue =  ($arrayMax - $arrayMin) / 2;
        //     $p_salary_array = [];

        //     foreach ($pvalues as $key => $value) {
        //         if ($value >= $arrayCenterValue) {
        //             $p_salary =  ($max - $mid) * ($value / 100 - ($arrayMax / 100 - $arrayMin / 100) / 2) / (($arrayMax / 100 - $arrayMin / 100) / 2) + $mid;

        //             array_push($p_salary_array, $p_salary);
        //         } else {
        //             $p_salary = ($mid - $min) * ($value / 100 - $arrayMin / 100) / (($arrayMax / 100 - $arrayMin / 100) / 2) + $min;
        //             array_push($p_salary_array, $p_salary);
        //         }
        //     }
        //     $p_salary_array = array_reverse($p_salary_array);
        //     array_push($p_salary_array, 0);
        //     $p_salary_array = array_reverse($p_salary_array);

        //     //A 績效
        //     $atestId = $employee_score->score_id;


        //     $ptest = CompanyPTest::orderBy('sort', 'DESC')->pluck('id');
        //     foreach ($p_salary_array as $key => $value) {
        //         if (array_sum($salaries) >= $value) {
        //             $ptestId = $ptest[$key];
        //         }
        //     }
        //     if (CompanyDTestCompanyPTest::where('company_d_test_id', $atestId)->where('company_p_test_id', $ptestId)->first()) {
        //         $p_numbering = CompanyDTestCompanyPTest::where('company_d_test_id', $atestId)->where('company_p_test_id', $ptestId)->first()->score;
        //     } else {
        //         $p_numbering = 0;
        //     }

        //     $a_name = CompanyATest::first()->name;

        //     // if ($employee_score->score_id) {
        //     //     // dd($employee_score->score_id);
        //     //     $a_name = CompanyATest::find($employee_score->score_id)->name;
        //     // }


        //     $a_numbering = CompanyATest::where('name', $a_name)->first()->numbering;

        //     $e_numbering = CompanyATest::where('name', $a_name)->first()->payment_parameter * $employee_score->company_bonus->all_numbering;;

        //     // $data['score_id'] = $employee_score->score;
        //     // $data['score'] = ATest::find($request->score)->name;
        //     $data['location_p'] = $ptestId;
        //     $data['location_c'] = $ctestId;
        //     $data['bonus_c'] = $c_numbering * $employee_salary;
        //     $data['bonus_p'] = $p_numbering * $employee_salary;
        //     $data['bonus_a'] = $a_numbering * $employee_salary;

        //     $data['bonus_e'] = $e_numbering * $employee_salary;
        //     // if ($employee_score->is_management == 1) {
        //     $data['bonus_management'] = $management->fixed_amount;
        //     // }

        //     $data['bonus_special'] = $special->fixed_amount;
        //     $data['bonus_guaranteed'] = $guaranteed->fixed_amount;




        //     $employee_score->update($data);
        // }

        return Reply::success('<strong> ' . $request->frequency_name . '</strong> updated successfully');
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy($id)
    {
        if (Request::ajax()) {
            CompanyBonusSetting::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
