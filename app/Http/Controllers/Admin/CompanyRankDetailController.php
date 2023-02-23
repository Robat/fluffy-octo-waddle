<?php

namespace App\Http\Controllers\Admin;

use App\Models\Grade;
use App\Classes\Reply;
use Illuminate\Http\Request;
use App\Models\CompanyRankDetail;
use App\Services\CompanyRankDetailService;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;

class CompanyRankDetailController extends AdminBaseController
{
    private $companyRankDetailService;

    public function __construct(CompanyRankDetailService $companyRankDetailService)
    {
        parent::__construct();
        $this->departmentsOpen = 'active open';
        $this->pageTitle = '薪級表';
        $this->departmentsActive = 'active';

        $this->companyRankDetailService = $companyRankDetailService;
    }


    public function index($id)
    {
        $this->rank_id = $id;

        $grades = admin()->company->grades()->get(['id'])->toArray();

        $this->companyRankDetailService->upsertCompanyRankDetails(admin()->company->frequency()->id, $id, $grades);

        return view('admin.rank_details.index', $this->data);
    }


    public function ajax_index($rank_id)
    {
        $result = CompanyRankDetail::with('grade')
            ->select('id', 'rank_id', 'grade_id', 'salary_max', 'salary_min', 'salary_mid')
            ->where('rank_id', $rank_id);

        // 在这里，您可以使用 $rank_id 来进行数据查询等操作

        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('grade', function ($row) {
                return $row->grade->grade;
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn move-up">Up</button><button class="btn move-down">Down</button><a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-edit"></i> 編輯</a>
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
     * @param  \App\Models\CompanyRankDetail  $companyRankDetail
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyRankDetail $companyRankDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyRankDetail  $companyRankDetail
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->grade = CompanyRankDetail::find($id);
        return View::make('admin.rank_details.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyRankDetail  $companyRankDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $grade = CompanyRankDetail::findOrFail($id);
        $grade->update($request->toArray());

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyRankDetail  $companyRankDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyRankDetail $companyRankDetail)
    {
        //
    }
}
