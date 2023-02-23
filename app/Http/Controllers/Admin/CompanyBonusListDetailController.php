<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompanyBonusListDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;

class CompanyBonusListDetailController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->adiffOpen = 'active open';
        $this->pageTitle = '公司獎金明細表';
        $this->adiffActive = 'active';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $this->bonus_id = $id;
        return View::make('admin.bonus_list_details.index', $this->data);
    }

    public function ajax_index($bonus_id)
    {
        $select = [
            'company_bonus_list_details.id', 'company_bonus_list_details.bonus_list_id', 'company_bonus_list_details.bonus_id', 'company_bonus_list_details.employee_id',
            'company_bonus_list_details.salary'
        ];


        $result = CompanyBonusListDetail::with(['employee', 'employee.department', 'employee.designation', 'employee.employee_salaries', 'employee.employee_score', 'employee.designation.grade', 'company_bonus', 'company_bonus.rank'])->select($select)
            ->where('bonus_list_id', $bonus_id)
            ->leftJoin('employees', 'employees.id', '=', 'company_bonus_list_details.employee_id');


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
                return '';
                //evaluating
            })
            ->editColumn('person_total', function ($row) {
                return '';
            })
            ->editColumn('total', function ($row) {
                return '';
            })
            ->editColumn('general', function ($row) {
                return '';
            })
            ->editColumn('salary_table', function ($row) {
                return '';
                // p的獎金月數
            })

            ->editColumn('salary_table_c', function ($row) {
                return '';
            })
            ->editColumn('total_numbering', function ($row) {
                return '';
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
     * @param  \App\Models\CompanyBonusListDetail  $companyBonusListDetail
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyBonusListDetail $companyBonusListDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyBonusListDetail  $companyBonusListDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyBonusListDetail $companyBonusListDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyBonusListDetail  $companyBonusListDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyBonusListDetail $companyBonusListDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyBonusListDetail  $companyBonusListDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyBonusListDetail $companyBonusListDetail)
    {
        //
    }
}
