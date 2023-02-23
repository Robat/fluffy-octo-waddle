<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\CompanyDTest;
use App\Models\CompanyPTest;
use Illuminate\Http\Request;
use App\Models\CompanyDTestCompanyPTest;
use App\Http\Controllers\AdminBaseController;

class CompanyDTestCompanyPTestController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->salariesOpen = 'active open';
        $this->pageTitle = 'DP';
        $this->totalAmountActive = 'active';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $this->company_dtests = CompanyDTest::with('company_ptests')->where('bonus_calculation_id', $id)->get();

        if (isset(admin()->company->frequency()->id)) {
            $this->company_ptests = CompanyPTest::where('frequency_id', admin()->company->frequency()->id)->with('company_dtests')->where('bonus_calculation_id', $id)->get();
        } else {
            $this->company_ptests = CompanyPTest::with('company_dtests')->where('bonus_calculation_id', $id)->get();
        }
        return view('admin.company_dptests.index', $this->data);
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
     * @param  \App\Models\CompanyDTestCompanyPTest  $companyDTestCompanyPTest
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyDTestCompanyPTest $companyDTestCompanyPTest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyDTestCompanyPTest  $companyDTestCompanyPTest
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyDTestCompanyPTest $companyDTestCompanyPTest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyDTestCompanyPTest  $companyDTestCompanyPTest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyDTestCompanyPTest $companyDTestCompanyPTest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyDTestCompanyPTest  $companyDTestCompanyPTest
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyDTestCompanyPTest $companyDTestCompanyPTest)
    {
        //
    }
}
