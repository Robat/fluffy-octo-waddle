<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use App\Models\CompanyTotalAmountMethod;
use App\Classes\Reply;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;


class CompanyTotalAmountMethodController extends AdminBaseController
{
    // 總金額
    public function __construct()
    {
        parent::__construct();
        $this->salariesOpen = 'active open';
        $this->pageTitle = '總金額制';
        $this->totalAmountActive = 'active';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.total_amount.index', $this->data);
    }

    public function ajax_index()
    {
        $result = CompanyTotalAmountMethod::select(['id', 'frequency_id', 'total_amount', 'created_at', 'updated_at']);


        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->total_amount . '\')"><i class="fa fa-edit"></i> Edit</a>

                                  <a href="javascript:void(0)" class="btn btn-danger" onclick="del(' . $row->id . ',\'' . $row->total_amount . '\')"><i class="fa fa-trash"></i> Delete</a>';
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
     * @param  \App\Models\CompanyTotalAmountMethod  $companyTotalAmountMethod
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyTotalAmountMethod $companyTotalAmountMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyTotalAmountMethod  $companyTotalAmountMethod
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyTotalAmountMethod $companyTotalAmountMethod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyTotalAmountMethod  $companyTotalAmountMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyTotalAmountMethod $companyTotalAmountMethod)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyTotalAmountMethod  $companyTotalAmountMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyTotalAmountMethod $companyTotalAmountMethod)
    {
        //
    }
}
