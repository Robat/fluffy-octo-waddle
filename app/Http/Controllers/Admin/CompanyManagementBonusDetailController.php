<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Reply;
use App\Models\Designation;
use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;

use App\Models\CompanyManagementBonusDetail;
use App\Http\Controllers\AdminBaseController;

class CompanyManagementBonusDetailController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->departmentsOpen = 'active open';
        $this->pageTitle = '管理職責任獎金';
        $this->departmentsActive = 'active';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $this->management_id  = $id;

        $designations = Designation::select('id')->where('company_id', admin()->company->id)->get()->toArray();

        $tests = [];
        for ($i = 0; $i < count($designations); $i++) {
            CompanyManagementBonusDetail::updateOrCreate(
                [
                    'frequency_id' => admin()->company->frequency()->id,
                    'management_id' => $id,
                    'designation_id' => $designations[$i]['id']
                ],

            );
        }


        return View::make('admin.management_bonus_details.index', $this->data);
    }

    public function ajax_index($management_id)
    {
        // $result = Grade::select('id', 'grade', 'salary_max', 'salary_mid', 'salary_min', 'created_at', 'updated_at');

        $result = CompanyManagementBonusDetail::with('designation', 'designation.members')->select('id', 'management_id', 'designation_id', 'fixed_amount', 'number', 'subtotal')->where('management_id', $management_id);

        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('designation', function ($row) {
                return $row->designation->designation;
            })
            ->addColumn('number', function ($row) {
                return count($row->designation->members);
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
     * @param  \App\Models\CompanyManagementBonusDetail  $companyManagementBonusDetail
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyManagementBonusDetail $companyManagementBonusDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyManagementBonusDetail  $companyManagementBonusDetail
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->management = CompanyManagementBonusDetail::find($id);
        return View::make('admin.management_bonus_details.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyManagementBonusDetail  $companyManagementBonusDetail
     * @return \Illuminate\Http\Response
     */
    public function update(\Illuminate\Http\Request $request, $id)
    {
        $management = CompanyManagementBonusDetail::find($id);


        $management->update($request->toArray());


        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyManagementBonusDetail  $companyManagementBonusDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyManagementBonusDetail $companyManagementBonusDetail)
    {
        //
    }
}
