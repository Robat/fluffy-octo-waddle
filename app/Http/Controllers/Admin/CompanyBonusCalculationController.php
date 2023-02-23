<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompanyBonusCalculation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AdminBaseController;

class CompanyBonusCalculationController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->Open = 'active open';
        $this->pageTitle = '個人獎金計算方式';
        $this->Active = 'active';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.bonus_calculation.index', $this->data);
    }


    public function ajax_index()
    {
        $result = CompanyBonusCalculation::select(['id', 'frequency_id', 'bonus_setting_id', 'method', 'status', 'created_at', 'updated_at']);

        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showPerformance(' . $row->id . ')"><i class="fa fa-edit"></i> Performance</a>

                <a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEvaluating(' . $row->id . ')"><i class="fa fa-edit"></i> Evaluating</a>

                <a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showCValue(' . $row->id . ')"><i class="fa fa-edit"></i> 填入CR值</a>

                <a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showPValue(' . $row->id . ')"><i class="fa fa-edit"></i> 填入P值</a>

                <a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showCenter(' . $row->id . ')"><i class="fa fa-edit"></i> Center Point</a>



                ';
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
     * @param  \App\Models\CompanyBonusCalculation  $companyBonusCalculation
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyBonusCalculation $companyBonusCalculation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyBonusCalculation  $companyBonusCalculation
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyBonusCalculation $companyBonusCalculation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyBonusCalculation  $companyBonusCalculation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyBonusCalculation $companyBonusCalculation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyBonusCalculation  $companyBonusCalculation
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyBonusCalculation $companyBonusCalculation)
    {
        //
    }
}
