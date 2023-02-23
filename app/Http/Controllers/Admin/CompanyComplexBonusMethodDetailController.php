<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Models\CompanyComplexBonusMethodDetail;

class CompanyComplexBonusMethodDetailController extends AdminBaseController
{


    public function __construct()
    {
        parent::__construct();
        $this->departmentsOpen = 'active open';
        $this->pageTitle = '複雜月數制';
        $this->departmentsActive = 'active';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $this->rank_id  = $id;

        $departments = Department::select('id')->where('company_id', admin()->company->id)->get()->toArray();

        $tests = [];
        for ($i = 0; $i < count($departments); $i++) {
            CompanyComplexBonusMethodDetail::updateOrCreate(
                [
                    'frequency_id' => admin()->company->frequency()->id,
                    'complex_id' => $id,
                    'department_id' => $departments[$i]['id']
                ],

            );
        }
        return View::make('admin.complex_bonus_details.index', $this->data);
    }
    public function ajax_index($complex_id)
    {
        // $result = Grade::select('id', 'grade', 'salary_max', 'salary_mid', 'salary_min', 'created_at', 'updated_at');

        $result = CompanyComplexBonusMethodDetail::with(['department', 'department.members'])->select('id', 'complex_id', 'department_id', 'numbering', 'number', 'subtotal')->where('complex_id', $complex_id);

        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('department', function ($row) {
                return $row->department->deptName;
            })
            ->addColumn('number', function ($row) {
                return count($row->department->members);
            })
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
     * @param  \App\Models\CompanyComplexBonusMethodDetail  $companyComplexBonusMethodDetail
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyComplexBonusMethodDetail $companyComplexBonusMethodDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyComplexBonusMethodDetail  $companyComplexBonusMethodDetail
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->department = CompanyComplexBonusMethodDetail::find($id);
        return View::make('admin.complex_bonus_details.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyComplexBonusMethodDetail  $companyComplexBonusMethodDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $department = CompanyComplexBonusMethodDetail::find($id);


        $department->update($request->toArray());


        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyComplexBonusMethodDetail  $companyComplexBonusMethodDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyComplexBonusMethodDetail $companyComplexBonusMethodDetail)
    {
        //
    }
}
