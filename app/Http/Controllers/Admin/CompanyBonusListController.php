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
use Illuminate\Http\Request;
use App\Models\CompanyBonusList;
use Illuminate\Support\Facades\DB;
use App\Models\CompanyBonusSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;

class CompanyBonusListController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->adiffOpen = 'active open';
        $this->pageTitle = '獎金明細表';
        $this->adiffActive = 'active';
    }
    public function index()
    {
        // $c = new CompanyBonusList();

        // $c->employee_id = 31;

        // $c->save();

        return View::make('admin.bonus_list.index', $this->data);
    }


    public function ajax_index()
    {

        $select = [
            'company_bonus_lists.id', 'company_bonus_lists.bonus_id', 'company_bonus_lists.employee_id'
        ];

        $result = CompanyBonusList::with(['employee', 'employee.department', 'employee.designation', 'employee.employee_salaries', 'employee.employee_score', 'employee.designation.grade', 'company_bonus', 'company_bonus.rank'])->select($select)
            ->where('bonus_id', 37)
            ->leftJoin('employees', 'employees.id', '=', 'company_bonus_lists.employee_id');

        return DataTables::of($result)
            ->addIndexColumn()
            ->editColumn('employeeID', function ($row) {
                return $row->employee->employeeID;
            })
            ->editColumn('fullName', function ($row) {
                return $row->employee->fullName;
            })
            ->editColumn('department', function ($row) {
                return $row->employee->department->deptName;
            })
            ->editColumn('grade', function ($row) {
                return $row->employee->designation->grade->grade;
            })
            ->editColumn('designation', function ($row) {
                return $row->employee->designation->designation;
            })
            ->editColumn('salary', function ($row) {
                $this->salaries = $row->employee->employee_salaries->pluck('salary')->toArray();
                return array_sum($this->salaries);
            })
            ->editColumn('bonus', function ($row) {
                if (isset($row->employee->employee_score)) {
                    return $row->employee->employee_score->score;
                } else {
                    return '';
                }
            })
            ->editColumn('special', function ($row) {
                if (isset($row->employee->employee_score)) {
                    $this->special = $row->employee->employee_score->bonus_special;
                    return $this->special;
                } else {
                    return '';
                }
            })
            ->editColumn('guaranteed', function ($row) {
                if (isset($row->employee->employee_score)) {
                    $this->guaranteed = $row->employee->employee_score->bonus_guaranteed;
                    return $this->guaranteed;
                } else {
                    return '';
                }
            })
            ->editColumn('management', function ($row) {
                if (isset($row->employee->employee_score)) {
                    $this->management = $row->employee->employee_score->bonus_management;
                    return $this->management;
                } else {
                    return '';
                }
            })

            ->editColumn('bonus_numbering', function ($row) {
                $this->performance = 0;
                if (isset($row->employee->employee_score)) {
                    if ($row->company_bonus->personex == 'performance') {
                        if ($row->employee->employee_score->score) {
                            $this->performance = ATest::where('name', $row->employee->employee_score->score)->first()->numbering;
                        } else {
                            $this->performance = 0;
                        }
                    }
                    // evaluating
                    if ($row->company_bonus->personex == 'evaluating') {
                        if ($row->employee->employee_score->score) {
                            $this->performance = ATest::where('name', $row->employee->employee_score->score)->first()->payment_parameter * $row->company_bonus->all_numbering;
                        } else {
                            $this->performance = 0;
                        }
                    }
                    if ($row->company_bonus->personex == 'cvalue') {
                        if (isset($row->employee->employee_score)) {
                            $cvalues = CValue::pluck('mark')->toArray();
                            $c_center = CValue::where('center', '1')->first()->mark;
                            $grade = Grade::where('grade', $row->employee->designation->grade->grade)->pluck('id')->first();
                            $max = $row->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_max;
                            $mid = $row->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_mid;
                            $min = $row->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_min;

                            $c_salary_array = [];

                            foreach ($cvalues as $key => $value) {
                                // $c_salary =  round($value * $mid * (1 / $c_center), 2);
                                $c_salary =  round($value * $mid, 2);
                                array_push($c_salary_array, $c_salary);
                            }

                            array_push($c_salary_array, 0);
                            $c_salary_array = array_reverse($c_salary_array);

                            $atestId = $row->employee->employee_score->score_id;
                            $ctest = CTest::orderBy('sort', 'DESC')->pluck('id');
                            foreach ($c_salary_array as $key => $value) {
                                if (array_sum($this->salaries) >= $value) {
                                    $ctestId = $ctest[$key];
                                }
                            }
                            $c_numbering = ATestCTest::where('a_test_id', $atestId)->where('c_test_id', $ctestId)->first()->score;
                            // c的獎金月數

                            $this->performance = $c_numbering;
                        } else {
                            $this->performance =  '';
                        }
                    }
                    if ($row->company_bonus->personex == 'pvalue') {

                        if (isset($row->employee->employee_score)) {
                            $pvalues = PValue::pluck('mark')->toArray();
                            $p_center = PValue::where('center', '1')->first()->mark;
                            $grade = Grade::where('grade', $row->employee->designation->grade->grade)->pluck('id')->first();
                            $max = $row->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_max;
                            $mid = $row->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_mid;
                            $min = $row->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_min;

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
                            $atestId = $row->employee->employee_score->score_id;
                            $ptest = PTest::orderBy('sort', 'DESC')->pluck('id');
                            foreach ($p_salary_array as $key => $value) {
                                if (array_sum($this->salaries) >= $value) {
                                    $ptestId = $ptest[$key];
                                }
                            }
                            $p_numbering = ATestPTest::where('a_test_id', $atestId)->where('p_test_id', $ptestId)->first()->score;

                            $this->performance = $p_numbering;
                        } else {
                            $this->performance = '';
                        }
                        // p的獎金月數

                    }
                    return $this->performance;
                } else {
                    return '';
                }
                //evaluating
            })
            ->editColumn('person_total', function ($row) {
                if (isset($row->employee->employee_score)) {
                    $this->total = $this->performance * array_sum($this->salaries);
                    return $this->total;
                } else {
                    return '';
                }
            })
            ->editColumn('total', function ($row) {
                if (isset($row->employee->employee_score)) {
                    $this->total = $this->performance * array_sum($this->salaries) + $this->special + $this->management;
                    return '<input name="total[' . $row->employee->id . ']" value="' . $this->total . '">';
                } else {
                    return '';
                }
            })
            ->editColumn('general', function ($row) {
                return '';
            })
            ->editColumn('salary_table', function ($row) {
                if (isset($row->employee->employee_score)) {
                    $pvalues = PValue::pluck('mark')->toArray();
                    $p_center = PValue::where('center', '1')->first()->mark;
                    $grade = Grade::where('grade', $row->employee->designation->grade->grade)->pluck('id')->first();
                    $max = $row->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_max;
                    $mid = $row->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_mid;
                    $min = $row->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_min;

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
                    $atestId = $row->employee->employee_score->score_id;
                    $ptest = PTest::orderBy('sort', 'DESC')->pluck('id');
                    foreach ($p_salary_array as $key => $value) {
                        if (array_sum($this->salaries) >= $value) {
                            $ptestId = $ptest[$key];
                        }
                    }
                    if ($row->employee->employee_score->score_id) {
                        $p_numbering = ATestPTest::where('a_test_id', $atestId)->where('p_test_id', $ptestId)->first()->score;
                    } else {
                        $p_numbering = 0;
                    }
                    return  $p_numbering;
                } else {
                    return '';
                }
                // p的獎金月數
            })

            ->editColumn('salary_table_c', function ($row) {
                if (isset($row->employee->employee_score)) {
                    $cvalues = CValue::pluck('mark')->toArray();
                    $c_center = CValue::where('center', '1')->first()->mark;
                    $grade = Grade::where('grade', $row->employee->designation->grade->grade)->pluck('id')->first();
                    $max = $row->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_max;
                    $mid = $row->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_mid;
                    $min = $row->company_bonus->rank->details->where('grade_id',  $grade)->first()->salary_min;

                    $c_salary_array = [];

                    foreach ($cvalues as $key => $value) {
                        $c_salary =  round($value * $mid * (1 / $c_center), 2);
                        array_push($c_salary_array, $c_salary);
                    }

                    array_push($c_salary_array, 0);
                    $c_salary_array = array_reverse($c_salary_array);

                    $atestId = $row->employee->employee_score->score_id;
                    $ctest = CTest::orderBy('sort', 'DESC')->pluck('id');
                    foreach ($c_salary_array as $key => $value) {
                        if (array_sum($this->salaries) >= $value) {
                            $ctestId = $ctest[$key];
                        }
                    }
                    if ($row->employee->employee_score->score_id) {
                        $c_numbering = ATestCTest::where('a_test_id', $atestId)->where('c_test_id', $ctestId)->first()->score;
                    } else {
                        $c_numbering = 0;
                    }

                    // c的獎金月數

                    return   $c_numbering;
                } else {
                    return '';
                }
            })
            ->editColumn('total_numbering', function ($row) {
                if (array_sum($this->salaries) > 0) {
                    return $this->total / array_sum($this->salaries);
                } else {
                    return 0;
                }
            })
            ->editColumn('method', function ($row) {
                return $row->company_bonus->personex;
            })
            ->make(true);
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
     * @param  \App\Models\CompanyBonusList  $companyBonusList
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyBonusList $companyBonusList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyBonusList  $companyBonusList
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyBonusList $companyBonusList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyBonusList  $companyBonusList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyBonusList $companyBonusList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyBonusList  $companyBonusList
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyBonusList $companyBonusList)
    {
        //
    }
    public function updateAll(Request $request)
    {
        // dd($request->all());
        $employees = Employee::select('id')->get();
        $bonus_id = CompanyBonusSetting::bonusId();
        foreach ($employees as $employee) {
            CompanyBonusList::UpdateOrCreate([
                'employee_id' => $employee->id,
                'bonus_id' => $bonus_id,
                'bonus' => $bonus_id,
            ]);
        }
    }
}
