<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmployeeBonusList;
use App\Models\CompanyBonusSetting;
use App\Models\EmployeeBonusDetail;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;

class EmployeeBonusDetailController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->adiffOpen = 'active open';
        $this->pageTitle = '獎金明細表';
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

        // dd($this->bonus_id);
        // $test = EmployeeBonusDetail::where('employee_bonus_id', 1)->get()->toArray();
        // dd($test);
        return View::make('admin.employee_bonus_details.index', $this->data);
    }


    public function ajax_index($bonus_id)
    {
        $result = EmployeeBonusDetail::select(['id', 'created_at', 'updated_at', 'employee_bonus_id'])->where('employee_bonus_id', $bonus_id);


        return DataTables::of($result)
            ->addIndexColumn()->addColumn('action', function ($row) {
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
     * @param  \App\Models\EmployeeBonusDetail  $employeeBonusDetail
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeBonusDetail $employeeBonusDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeBonusDetail  $employeeBonusDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeBonusDetail $employeeBonusDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeBonusDetail  $employeeBonusDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeBonusDetail $employeeBonusDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeBonusDetail  $employeeBonusDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeBonusDetail $employeeBonusDetail)
    {
        //
    }


    public function updateAll(Request $request, $id)
    {
        // dd($request->all());
        $employees = Employee::select('id')->get();

        foreach ($employees as $employee) {
            EmployeeBonusDetail::UpdateOrCreate([
                'employee_id' => $employee->id,
                'employee_bonus_id' => $id,
                'frequency_id' => admin()->company->frequency()->id,

            ]);
        }

        return Reply::success('<strong></strong> ');
    }
}
