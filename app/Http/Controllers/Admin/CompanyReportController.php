<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\CompanyReport;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;

class CompanyReportController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function depbase()
    {


        $this->departments = Department::with(['members', 'members.bonus'])->get();



        return View::make('admin.reports.depbase.index', $this->data);
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
     * @param  \App\Models\CompanyReport  $companyReport
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyReport $companyReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyReport  $companyReport
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyReport $companyReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyReport  $companyReport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyReport $companyReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyReport  $companyReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyReport $companyReport)
    {
        //
    }
}
