<?php

namespace App\Http\Controllers\Admin;


use App\Helpers\Reply;
use Illuminate\Support\Facades\View;
use App\Models\CompanyBusinessProfit;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\BusinessProfit\CreateRequest;
use App\Http\Requests\Admin\BusinessProfit\UpdateRequest;

class CompanyBusinessProfitController extends AdminBaseController
{
    protected $messages = [];

    public function __construct()
    {
        parent::__construct();
        $this->frequenciesOpen = 'active open';
        $this->pageTitle = '營業額';
        $this->frequenciesActive = 'active';
    }

    public function index()
    {
        return View::make('admin.business_profit.index', $this->data);
    }

    public function ajax_index()
    {
        // $result = Grade::select('id', 'grade', 'salary_max', 'salary_mid', 'salary_min', 'created_at', 'updated_at');

        $result = CompanyBusinessProfit::select('id', 'company_id', 'name', 'range', 'default');

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
        return View::make('admin.business_profit.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        CompanyBusinessProfit::create($request->toArray());

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyBusinessProfit  $companyBusinessProfit
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyBusinessProfit $companyBusinessProfit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyBusinessProfit  $companyBusinessProfit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->business_profit = CompanyBusinessProfit::find($id);
        return View::make('admin.business_profit.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyBusinessProfit  $companyBusinessProfit
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {

        $business_profit = CompanyBusinessProfit::findOrFail($id);
        $business_profit->update($request->toArray());

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyBusinessProfit  $companyBusinessProfit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Request::ajax()) {
            CompanyBusinessProfit::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
