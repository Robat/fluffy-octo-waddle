<?php

namespace App\Http\Controllers\Admin;

use App\Models\Grade;
use App\Classes\Reply;

use App\Models\CompanyRank;
use Illuminate\Http\Request;
use App\Models\CompanyRankDetail;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;

class CompanyRankController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->departmentsOpen = 'active open';
        $this->pageTitle = '薪級表';
        $this->departmentsActive = 'active';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.ranks.index', $this->data);
        // return '公司薪集表';
    }

    public function ajax_index()
    {
        // $result = Grade::select('id', 'grade', 'salary_max', 'salary_mid', 'salary_min', 'created_at', 'updated_at');

        $result = CompanyRank::select('id', 'name');

        return DataTables::of($result)
            ->addIndexColumn()
            ->editColumn('ranks', function ($row) {

                return '';
            })
            // ->editColumn('salaryMid', function ($row) {

            //     return $row->detail->salary_mid;
            // })
            // ->editColumn('salaryMin', function ($row) {

            //     return $row->detail->salary_min;
            // })
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showDetailEdit(' . $row->id . ')"><i class="fa fa-edit"></i> 編輯薪表</a>

                <a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-edit"></i> 編輯</a>

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
        return View::make('admin.ranks.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $grades = admin()->company->grades->pluck('id')->toArray();
        // $company_id = admin()->company_id;
        // $frequency_id = admin()->company->frequency()->id;

        // $data = [];

        // foreach ($grades as $grade) {
        //     array_push($data, array(
        //         'frequency_id'=>$frequency_id,
        //         'company_id'=>$company_id,
        //         'grade_id' => $grade,
        //         'name' => $request->name
        //     ));
        // }

        // CompanyRank::insert($data);
        $company_rank = CompanyRank::create($request->toArray());
        $grades = admin()->company->grades->pluck('id')->toArray(); //目前公司的職務等級
        $frequency_id = admin()->company->frequency()->id;

        $data = [];

        foreach ($grades as $grade) {
            array_push($data, array(
                'frequency_id' => $frequency_id,
                'rank_id' => $company_rank->id,
                'grade_id' => $grade
            ));
        }
        CompanyRankDetail::insert($data);
        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyRank  $companyRank
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->grade = Grade::find($id);
        return View::make('admin.ranks.show', $this->data);
    }

    public function edit($id)
    {

        $this->rank = CompanyRank::find($id);

        return View::make('admin.ranks.edit', $this->data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyRank  $companyRank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $rank = CompanyRank::findOrFail($id);
        $rank->name = $request->name;
        $rank->save();

        // $detail = $rank->detail;
        // $detail->salary_max = $request->salary_max;
        // $detail->salary_mid = $request->salary_mid;
        // $detail->salary_min = $request->salary_min;
        // $detail->save();

        return Reply::success('<strong> ' . $request->rank . '</strong> updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyRank  $companyRank
     * @return \Illuminate\Http\Response
     */
    // public function destroy(CompanyRank $companyRank)
    // {
    //     //
    // }

    public function destroy($id)
    {
        if (\Illuminate\Support\Facades\Request::ajax()) {
            CompanyRank::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
