<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\Department;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;

use Yajra\DataTables\Facades\DataTables;
use App\Models\CompanySpecialBonusDetail;
use App\Http\Controllers\AdminBaseController;

class CompanySpecialBonusDetailController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->departmentsOpen = 'active open';
        $this->pageTitle = '事業部特別獎金';
        $this->departmentsActive = 'active';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $this->special_id  = $id;

        $departments = Department::select('id')->where('company_id', admin()->company->id)->get()->toArray();

        $tests = [];
        for ($i = 0; $i < count($departments); $i++) {
            CompanySpecialBonusDetail::updateOrCreate(
                [
                    'frequency_id' => admin()->company->frequency()->id,
                    'special_id' => $id,
                    'department_id' => $departments[$i]['id']
                ],

            );
        }

        return View::make('admin.special_bonus_details.index', $this->data);
    }

    public function ajax_index($special_id)
    {
        // $result = Grade::select('id', 'grade', 'salary_max', 'salary_mid', 'salary_min', 'created_at', 'updated_at');

        $result = CompanySpecialBonusDetail::with(['department', 'department.members'])->select('id', 'special_id', 'department_id', 'fixed_amount', 'number', 'subtotal')->where('special_id', $special_id);

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
     * @param  \App\Models\CompanySpecialBonusDetail  $companySpecialBonusDetail
     * @return \Illuminate\Http\Response
     */
    public function show(CompanySpecialBonusDetail $companySpecialBonusDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanySpecialBonusDetail  $companySpecialBonusDetail
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->special = CompanySpecialBonusDetail::find($id);
        return View::make('admin.special_bonus_details.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanySpecialBonusDetail  $companySpecialBonusDetail
     * @return \Illuminate\Http\Response
     */
    public function update(\Illuminate\Http\Request  $request, $id)
    {
        $special = CompanySpecialBonusDetail::find($id);


        $special->update($request->toArray());


        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanySpecialBonusDetail  $companySpecialBonusDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Request::ajax()) {
            CompanySpecialBonusDetail::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
