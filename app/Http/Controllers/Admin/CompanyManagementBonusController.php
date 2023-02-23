<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Reply;
use Illuminate\Support\Facades\View;
use App\Models\CompanyManagementBonus;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;

use App\Models\CompanyManagementBonusDetail;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\CompanyManagement\CreateRequest;

class CompanyManagementBonusController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->frequenciesOpen = 'active open';
        $this->pageTitle = '管理職責任獎金';
        $this->frequenciesActive = 'active';
    }
    public function index()
    {
        return View::make('admin.management_bonus.index', $this->data);
    }


    public function ajax_index()
    {
        $result = CompanyManagementBonus::select(['id', 'name', 'status', 'created_at', 'updated_at']);

        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showDetailEdit(' . $row->id . ')"><i class="fa fa-edit"></i> 編輯Detail</a>

                <a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-edit"></i> Edit</a>

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
        return View::make('admin.management_bonus.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $management = CompanyManagementBonus::create($request->toArray());

        $designations = admin()->company->designations()->pluck('id')->toArray(); //目前公司的職務


        $frequency_id = admin()->company->frequency()->id;

        $data = [];

        foreach ($designations as $designation) {
            array_push($data, array(
                'frequency_id' => $frequency_id,
                'management_id' => $management->id,
                'designation_id' => $designation
            ));
        }

        CompanyManagementBonusDetail::insert($data);

        return Reply::success('<strong></strong> successfully added to the Database');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyManagementBonus  $companyManagementBonus
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyManagementBonus $companyManagementBonus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyManagementBonus  $companyManagementBonus
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->management = CompanyManagementBonus::find($id);
        return View::make('admin.management_bonus.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyManagementBonus  $companyManagementBonus
     * @return \Illuminate\Http\Response
     */
    public function update(\Illuminate\Http\Request  $request, $id)
    {
        $management = CompanyManagementBonus::findOrFail($id);

        $management->update([
            'name' => $request->name,
        ]);


        return Reply::success('<strong>{$request->deptName}</strong> updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyManagementBonus  $companyManagementBonus
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Request::ajax()) {
            CompanyManagementBonus::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
