<?php

namespace App\Http\Controllers\Admin;


use App\Helpers\Reply;
use App\Models\CompanyBenefit;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\Benefit\CreateRequest;
use App\Http\Requests\Admin\Benefit\UpdateRequest;

class CompanyBenefitController extends AdminBaseController
{
    protected $messages = [];

    public function __construct()
    {
        parent::__construct();
        $this->frequenciesOpen = 'active open';
        $this->pageTitle = '事業利益';
        $this->frequenciesActive = 'active';
    }

    public function index()
    {
        return View::make('admin.benefit.index', $this->data);
    }


    public function ajax_index()
    {
        // $result = Grade::select('id', 'grade', 'salary_max', 'salary_mid', 'salary_min', 'created_at', 'updated_at');

        $result = CompanyBenefit::select('id', 'company_id', 'name', 'range', 'default');

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
        return View::make('admin.benefit.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        CompanyBenefit::create($request->toArray());

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyBenefit  $companyBenefit
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyBenefit $companyBenefit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyBenefit  $companyBenefit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->benefit = CompanyBenefit::find($id);
        return View::make('admin.benefit.edit', $this->data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyBenefit  $companyBenefit
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {

        $benefit = CompanyBenefit::findOrFail($id);
        $benefit->update($request->toArray());

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyBenefit  $companyBenefit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Request::ajax()) {
            CompanyBenefit::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
