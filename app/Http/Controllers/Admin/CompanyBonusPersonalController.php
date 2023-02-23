<?php

namespace App\Http\Controllers\Admin;

use App\Models\ATest;
use App\Models\CTest;
use App\Models\PTest;
use App\Models\CValue;
use App\Models\Employee;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use App\Models\EmployeeScore;
use App\Models\EmployeeSalary;
use App\Models\CompanyBonusPersonal;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;


class CompanyBonusPersonalController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->atestOpen = 'active open';
        $this->pageTitle = '';
        $this->atestActive = 'active';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->employee_score = Employee::select('score')->pluck('score')->toArray();

        $this->employee_salary = EmployeeSalary::select('salary')->pluck('salary')->toArray();

        $this->atests = ATest::pluck('name')->toArray();

        $this->employeeScore_a = EmployeeScore::select('score')->get()->groupBy('score')->map->count();

        $employeeScores =  EmployeeScore::all();

        $new_score_array = array_flip($this->atests);
        foreach ($new_score_array as $key => $value) {
            $new_score_array[$key] = 0;
        }



        foreach ($employeeScores as  $key => $value) {
            $new_score_array[$value->score] = $new_score_array[$value->score] + $value->bonus_a;
        }

        $new_score_array_e = array_flip($this->atests);
        foreach ($new_score_array_e as $key => $value) {
            $new_score_array_e[$key] = 0;
        }





        foreach ($employeeScores as  $key => $value) {
            $new_score_array_e[$value->score] = $new_score_array_e[$value->score] + $value->bonus_e;
        }

        // dd($new_score_array);
        $this->new_score_array_a = $new_score_array;
        $this->new_score_array_e = $new_score_array_e;
        $this->employeeScore_a;
        // dd($this->employeeScore_a);
        // dd(EmployeeScore::select('score')->get()->toArray());

        $this->test = EmployeeScore::get()->toArray();

        $this->employeeScores = EmployeeScore::get();


        $this->ctests = CTest::pluck('name', 'id')->toArray();
        $this->ptests = PTest::pluck('name', 'id')->toArray();

        foreach ($this->ctests as $key => $ctest) {
            foreach ($this->atests as $atest) {
                $cr_array[$key][$atest] = 0;
                $cr_numbering_array[$key][$atest] = 0;
            }
        }

        foreach ($this->employeeScores as $key => $value) {
            if (in_array($value->location_c, array_keys($this->ctests))) {
                $cr_numbering_array[$value->location_c][$value->score] = $cr_numbering_array[$value->location_c][$value->score] + $value->bonus_c + $value->bonus_special + $value->bonus_management;
            } //管理職+ 部門
        }

        $this->cr_numbering_array = $cr_numbering_array;


        foreach ($this->employeeScores as $key => $value) {
            if (in_array($value->location_c, array_keys($this->ctests))) {
                $cr_array[$value->location_c][$value->score] = $cr_array[$value->location_c][$value->score] + 1;
            }
        }
        $this->cr_array = $cr_array;



        foreach ($this->ptests as $key => $ptest) {
            foreach ($this->atests as $atest) {
                $p_array[$key][$atest] = 0;
                $p_numbering_array[$key][$atest] = 0;
            }
        }

        foreach ($this->employeeScores as $key => $value) {
            if (in_array($value->location_p, array_keys($this->ptests))) {
                $p_numbering_array[$value->location_p][$value->score] = $p_numbering_array[$value->location_p][$value->score] + $value->bonus_p + $value->bonus_special + $value->bonus_management;;
            }
        }
        $this->p_numbering_array = $p_numbering_array;

        foreach ($this->employeeScores as $key => $value) {
            if (in_array($value->location_p, array_keys($this->ptests))) {
                $p_array[$value->location_p][$value->score] = $p_array[$value->location_p][$value->score] + 1;
            }
        }
        $this->p_array = $p_array;


        return View::make('admin.bdt_personal.index', $this->data);
    }


    public function ajax_index()
    {
        $result = CompanyBonusPersonal::select(['id',  'created_at', 'updated_at']);

        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-edit"></i> Edit</a>

                                  <a href="javascript:void(0)" class="btn btn-danger" onclick="del(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-trash"></i> Delete</a>';
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
     * @param  \App\Models\CompanyBonusPersonal  $companyBonusPersonal
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyBonusPersonal $companyBonusPersonal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyBonusPersonal  $companyBonusPersonal
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyBonusPersonal $companyBonusPersonal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyBonusPersonal  $companyBonusPersonal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyBonusPersonal $companyBonusPersonal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyBonusPersonal  $companyBonusPersonal
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyBonusPersonal $companyBonusPersonal)
    {
        //
    }
}
