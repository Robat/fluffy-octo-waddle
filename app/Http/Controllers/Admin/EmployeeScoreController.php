<?php

namespace App\Http\Controllers\Admin;

use App\Models\ATest;
use App\Models\CTest;
use App\Models\Grade;
use App\Models\PTest;
use App\Classes\Reply;
use App\Models\CValue;
use App\Models\PValue;
use App\Models\Employee;
use App\Models\ATestCTest;
use App\Models\ATestPTest;
use App\Models\EmployeeScore;
use App\Models\CompanyPerformance;
use App\Models\CompanyBonusSetting;
use Illuminate\Support\Facades\View;
use App\Models\CompanyBonusManagement;
use App\Models\CompanyGuaranteedBonus;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\CompanySpecialBonusDetail;
use App\Models\CompanyManagementBonusDetail;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\EmployeeScore\UpdateRequest;

class EmployeeScoreController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->gradesOpen = 'active open';
        $this->pageTitle = '員工績效';
        $this->gradesActive = 'active';
    }

    public function index()
    {
        // EmployeeScore::updateOrCreate(
        //     [
        //         'frequency_id' => admin()->company->frequency()->id,
        //         'employee_id' => $id
        //     ],
        //     [
        //         'score_id' => $request->score,
        //         'score' => ATest::find($request->score)->name
        //     ]
        // );
        // $bonus_setting = CompanyBonusSetting::select('id')->where('frequency_id', admin()->company->frequency()->id)->where('status', '1')->first();


        $employees = Employee::get()->toArray();
        $tests = [];
        for ($i = 0; $i < count($employees); $i++) {
            EmployeeScore::updateOrCreate(
                [
                    'frequency_id' => admin()->company->frequency()->id,
                    'employee_id' => $employees[$i]['id']
                ]
            );
        }






        return View::make('admin.employee_scores.index', $this->data);
    }

    public function ajax_employee_score()
    {

        $result = EmployeeScore::with(['employee', 'employee.designation', 'employee.department'])
            ->join('employees', 'employee_scores.employee_id', '=', 'employees.id')
            ->join('designations', 'employees.designation_id', '=', 'designations.id')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->select(['employee_scores.*', 'employees.employeeID', 'employees.fullName', 'designations.designation', 'departments.deptName']);

        return DataTables::of($result)
            ->addIndexColumn()

            ->addColumn('is_guaranteed', function ($employee_score) {
                $color = [0 => '', 1 => 'checked'];

                return '<input type="checkbox" onclick="changeGuaranteed(' . $employee_score->id . ',\'' . $employee_score->is_guaranteed . '\')"' . $color[$employee_score->is_guaranteed] . '>';
            })
            ->addColumn('is_special', function ($employee_score) {
                $color = [0 => '', 1 => 'checked'];

                return '<input type="checkbox" onclick="changeSpecial(' . $employee_score->id . ',\'' . $employee_score->is_special . '\')"' . $color[$employee_score->is_special] . '>';
            })
            ->addColumn('is_management', function ($employee_score) {
                $color = [0 => '', 1 => 'checked'];

                return '<input type="checkbox" onclick="changeManagement(' . $employee_score->id . ',\'' . $employee_score->is_management . '\')"' . $color[$employee_score->is_management] . '>';
            })
            ->addColumn('action', function ($employee_score) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $employee_score->id . ',\'' . $employee_score->name . '\')"><i class="fa fa-edit"></i> 編輯</a>

                                  <a href="javascript:void(0)" class="btn btn-danger" onclick="del(' . $employee_score->id . ',\'' . $employee_score->name . '\')"><i class="fa fa-trash"></i> 刪除</a>';
            })
            ->escapeColumns(['action'])
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeScore  $employeeScore
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeScore $employeeScore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeScore  $employeeScore
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->employee_score = EmployeeScore::find($id);
        // $this->atests = ATest::pluck('name', 'id')->toArray();
        $this->performances = CompanyPerformance::pluck('title', 'id')->toArray();
        return View::make('admin.employee_scores.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeScore  $employeeScore
     * @return \Illuminate\Http\Response
     *
     */
    public function update(UpdateRequest $request, $id)
    {


        $employee_score = EmployeeScore::find($id);
        $employee_salary = array_sum($employee_score->employee->employee_salaries->pluck('salary')->toArray());
        $data = $request->all();
        $designation = $employee_score->employee->designation->id;

        if ($employee_score->is_management == 1) {
            $management = CompanyManagementBonusDetail::where('frequency_id', admin()->company->frequency()->id)->where('designation_id', $designation)->first();
        }

        $department = $employee_score->employee->department->id;
        $special = CompanySpecialBonusDetail::where('frequency_id', admin()->company->frequency()->id)->where('department_id', $department)->first();
        $guaranteed = CompanyGuaranteedBonus::where('frequency_id', admin()->company->frequency()->id)->first();


        // // ******* //
        // // CR
        // $cvalues = CValue::pluck('mark')->toArray();
        // $c_center = CValue::where('center', '1')->first()->mark;
        // $grade = Grade::where('grade', $employee_score->employee->designation->grade->grade)->pluck('id')->first();

        // $salaries = $employee_score->employee->employee_salaries->pluck('salary')->toArray();
        // // dd($employee_score->company_bonus->id);
        // $max = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_max;

        // $mid = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_mid;
        // $min = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_min;

        // // dd($max . "-" . $mid . "-" . $min);

        // $c_salary_array = [];

        // foreach ($cvalues as $key => $value) {
        //     $c_salary =  round($value * $mid);
        //     array_push($c_salary_array, $c_salary);
        // }

        // // dd($c_salary_array);

        // array_push($c_salary_array, 0);
        // $c_salary_array = array_reverse($c_salary_array);

        // //目前綁定的績效ID 值

        // $atestId = $employee_score->employee->employee_score->score_id;
        // if ($atestId == null) {
        //     $atestId = $request->score;
        // }
        // $ctest = CTest::orderBy('sort', 'DESC')->pluck('id');

        // //由小到大排列 0,49600, 55800,68200, 74400
        // foreach ($c_salary_array as $key => $value) {
        //     if (array_sum($salaries) >= $value) {
        //         $ctestId = $ctest[$key];
        //     }
        // }




        // if (ATestCTest::where('a_test_id', $atestId)->where('c_test_id', $ctestId)->first()) {
        //     $c_numbering = ATestCTest::where('a_test_id', $atestId)->where('c_test_id', $ctestId)->first()->score;
        // } else {
        //     $c_numbering = '';
        // }

        // // dd($c_numbering);
        // // c的獎金月數

        // // dd($c_numbering);
        // // *******//
        // $pvalues = PValue::pluck('mark')->toArray();
        // $p_center = PValue::where('center', '1')->first()->mark;
        // $grade = Grade::where('grade', $employee_score->employee->designation->grade->grade)->pluck('id')->first();
        // $max = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_max;
        // $mid = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_mid;
        // $min = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_min;

        // $arrayMin = min($pvalues);
        // $arrayMax = max($pvalues);
        // $arrayCenterValue =  ($arrayMax - $arrayMin) / 2;
        // $p_salary_array = [];

        // foreach ($pvalues as $key => $value) {
        //     if ($value >= $arrayCenterValue) {
        //         $p_salary =  ($max - $mid) * ($value / 100 - ($arrayMax / 100 - $arrayMin / 100) / 2) / (($arrayMax / 100 - $arrayMin / 100) / 2) + $mid;

        //         array_push($p_salary_array, $p_salary);
        //     } else {
        //         $p_salary = ($mid - $min) * ($value / 100 - $arrayMin / 100) / (($arrayMax / 100 - $arrayMin / 100) / 2) + $min;
        //         array_push($p_salary_array, $p_salary);
        //     }
        // }
        // $p_salary_array = array_reverse($p_salary_array);
        // array_push($p_salary_array, 0);
        // $p_salary_array = array_reverse($p_salary_array);

        // //A 績效
        // $atestId = $employee_score->employee->employee_score->score_id;
        // if ($atestId == null) {
        //     $atestId = $request->score;
        // }

        // $ptest = PTest::orderBy('sort', 'DESC')->pluck('id');
        // foreach ($p_salary_array as $key => $value) {
        //     if (array_sum($salaries) >= $value) {
        //         $ptestId = $ptest[$key];
        //     }
        // }
        // if (ATestPTest::where('a_test_id', $atestId)->where('p_test_id', $ptestId)->first()) {
        //     $p_numbering = ATestPTest::where('a_test_id', $atestId)->where('p_test_id', $ptestId)->first()->score;
        // } else {
        //     $p_numbering = "";
        // }


        // $a_name = ATest::find($request->score)->name;

        // $a_numbering = ATest::where('name', $a_name)->first()->numbering;

        // $e_numbering = ATest::where('name', $a_name)->first()->payment_parameter * $employee_score->company_bonus->all_numbering;;

        $data['score_id'] = $request->score;
        $data['score'] = CompanyPerformance::find($request->score)->title;
        // $data['location_p'] = $ptestId;
        // $data['location_c'] = $ctestId;
        // $data['bonus_c'] = $c_numbering * $employee_salary;
        // $data['bonus_p'] = $p_numbering * $employee_salary;
        // $data['bonus_a'] = $a_numbering * $employee_salary;
        // $data['bonus_e'] = $e_numbering * $employee_salary;
        if ($employee_score->is_management == 1) {
            $data['bonus_management'] = $management->fixed_amount;
        }

        $data['bonus_special'] = $special->fixed_amount;
        $data['bonus_guaranteed'] = $guaranteed->fixed_amount;




        $employee_score->update($data);

        return Reply::success('messages.UpdateSuccess');
    }

    //全部更新
    public function updateAll()
    {
        $employeeScores = EmployeeScore::with(['employee', 'employee.employee_salaries', 'employee.designation', 'employee.department', 'employee.designation.grade', 'company_bonus'])->get();

        foreach ($employeeScores as $employee_score) {
            $employee_salary = array_sum($employee_score->employee->employee_salaries->pluck('salary')->toArray());

            $designation = $employee_score->employee->designation->id;


            $management = CompanyManagementBonusDetail::where('frequency_id', admin()->company->frequency()->id)->where('designation_id', $designation)->first();

            $guaranteed = CompanyGuaranteedBonus::where('frequency_id', admin()->company->frequency()->id)->first();
            $department = $employee_score->employee->department->id;
            $special = CompanySpecialBonusDetail::where('frequency_id', admin()->company->frequency()->id)->where('department_id', $department)->first();

            // ******* //
            // CR
            $cvalues = CValue::pluck('mark')->toArray();
            $c_center = CValue::where('center', '1')->first()->mark;
            $grade = Grade::where('grade', $employee_score->employee->designation->grade->grade)->pluck('id')->first();

            $salaries = $employee_score->employee->employee_salaries->pluck('salary')->toArray();
            // dd($employee_score);
            $max = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_max;

            $mid = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_mid;
            $min = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_min;

            // dd($max . "-" . $mid . "-" . $min);

            $c_salary_array = [];

            foreach ($cvalues as $key => $value) {
                $c_salary =  round($value * $mid);
                array_push($c_salary_array, $c_salary);
            }

            // dd($c_salary_array);

            array_push($c_salary_array, 0);
            $c_salary_array = array_reverse($c_salary_array);

            //目前綁定的績效ID 值


            $atestId = $employee_score->score_id;



            $ctest = CTest::orderBy('sort', 'DESC')->pluck('id');

            //由小到大排列 0,49600, 55800,68200, 74400
            foreach ($c_salary_array as $key => $value) {
                if (array_sum($salaries) >= $value) {
                    $ctestId = $ctest[$key];
                }
            }


            if (ATestCTest::where('a_test_id', $atestId)->where('c_test_id', $ctestId)->first()) {
                $c_numbering = ATestCTest::where('a_test_id', $atestId)->where('c_test_id', $ctestId)->first()->score;
            } else {
                $c_numbering = 0;
            }

            // dd($c_numbering);
            // c的獎金月數

            // dd($c_numbering);
            // *******//
            $pvalues = PValue::pluck('mark')->toArray();
            $p_center = PValue::where('center', '1')->first()->mark;
            $grade = Grade::where('grade', $employee_score->employee->designation->grade->grade)->pluck('id')->first();
            $max = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_max;
            $mid = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_mid;
            $min = $employee_score->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_min;

            $arrayMin = min($pvalues);
            $arrayMax = max($pvalues);
            $arrayCenterValue =  ($arrayMax - $arrayMin) / 2;
            $p_salary_array = [];

            foreach ($pvalues as $key => $value) {
                if ($value >= $arrayCenterValue) {
                    $p_salary =  ($max - $mid) * ($value / 100 - ($arrayMax / 100 - $arrayMin / 100) / 2) / (($arrayMax / 100 - $arrayMin / 100) / 2) + $mid;

                    array_push($p_salary_array, $p_salary);
                } else {
                    $p_salary = ($mid - $min) * ($value / 100 - $arrayMin / 100) / (($arrayMax / 100 - $arrayMin / 100) / 2) + $min;
                    array_push($p_salary_array, $p_salary);
                }
            }
            $p_salary_array = array_reverse($p_salary_array);
            array_push($p_salary_array, 0);
            $p_salary_array = array_reverse($p_salary_array);

            //A 績效
            $atestId = $employee_score->score_id;


            $ptest = PTest::orderBy('sort', 'DESC')->pluck('id');
            foreach ($p_salary_array as $key => $value) {
                if (array_sum($salaries) >= $value) {
                    $ptestId = $ptest[$key];
                }
            }
            if (ATestPTest::where('a_test_id', $atestId)->where('p_test_id', $ptestId)->first()) {
                $p_numbering = ATestPTest::where('a_test_id', $atestId)->where('p_test_id', $ptestId)->first()->score;
            } else {
                $p_numbering = 0;
            }

            $a_name = ATest::first()->name;

            if ($employee_score->score_id) {
                $a_name = ATest::find($employee_score->score_id)->name;
            }


            $a_numbering = ATest::where('name', $a_name)->first()->numbering;

            $e_numbering = ATest::where('name', $a_name)->first()->payment_parameter * $employee_score->company_bonus->all_numbering;;

            // $data['score_id'] = $employee_score->score;
            // $data['score'] = ATest::find($request->score)->name;
            $data['location_p'] = $ptestId;
            $data['location_c'] = $ctestId;
            $data['bonus_c'] = $c_numbering * $employee_salary;
            $data['bonus_p'] = $p_numbering * $employee_salary;
            $data['bonus_a'] = $a_numbering * $employee_salary;

            $data['bonus_e'] = $e_numbering * $employee_salary;
            // if ($employee_score->is_management == 1) {
            $data['bonus_management'] = $management->fixed_amount;
            // }

            $data['bonus_special'] = $special->fixed_amount;
            $data['bonus_guaranteed'] = $guaranteed->fixed_amount;




            $employee_score->update($data);
        }
    }


    public function changeManagement($id)
    {
        $employee_score = EmployeeScore::findOrFail($id);
        $is_management = ($employee_score->is_management == 1) ? 0 : 1;
        $employee_score->is_management = $is_management;
        $employee_score->save();

        return Reply::success('<strong> ' . '' . '</strong> updated successfully');
    }

    // 保證獎金
    public function changeGuaranteed($id)
    {
        $employee_score = EmployeeScore::findOrFail($id);
        $is_guaranteed = ($employee_score->is_guaranteed == 1) ? 0 : 1;
        $employee_score->is_guaranteed = $is_guaranteed;
        $employee_score->save();

        return Reply::success('<strong> ' . '' . '</strong> updated successfully');
    }

    //特別獎金
    public function changeSpecial($id)
    {
        $employee_score = EmployeeScore::findOrFail($id);
        $is_special = ($employee_score->is_special == 1) ? 0 : 1;
        $employee_score->is_special = $is_special;
        $employee_score->save();

        return Reply::success('<strong> ' . '' . '</strong> updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeScore  $employeeScore
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Request::ajax()) {
            EmployeeScore::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
