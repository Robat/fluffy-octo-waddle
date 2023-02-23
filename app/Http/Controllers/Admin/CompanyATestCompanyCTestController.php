<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\CompanyATest;
use App\Models\CompanyCTest;
use Illuminate\Http\Request;
use App\Models\CompanyCenterPoint;
use App\Models\CompanyATestCompanyCTest;
use App\Http\Controllers\AdminBaseController;

class CompanyATestCompanyCTestController extends  AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->salariesOpen = 'active open';
        $this->pageTitle = 'AC';
        $this->totalAmountActive = 'active';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $this->company_atests = CompanyATest::with('company_ctests')->where('bonus_calculation_id', $id)->get();


        if (isset(admin()->company->frequency()->id)) {
            $this->company_ctests = CompanyCTest::where('frequency_id', admin()->company->frequency()->id)->with('company_atests')->where('bonus_calculation_id', $id)->get();
        } else {
            $this->company_ctests = CompanyCTest::with('company_atests')->where('bonus_calculation_id', $id)->get();
        }
        return view('admin.company_actests.index', $this->data);
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
     * @param  \App\Models\CompanyATestCompanyCTest  $companyATestCompanyCTest
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyATestCompanyCTest $companyATestCompanyCTest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyATestCompanyCTest  $companyATestCompanyCTest
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->actest_id = $id;
        $this->company_atests = CompanyATest::with('company_ctests')->where('bonus_calculation_id', '=', $id)->orderBy('sort', 'ASC')->get();
        $this->company_ctests = CompanyCTest::where('frequency_id', admin()->company->frequency()->id)->with('company_atests')->where('bonus_calculation_id', '=', $id)->orderBy('sort', 'ASC')->get();
        $this->point = CompanyCenterPoint::where('category', 'c')->where('status', '0')->first();

        return view('admin.company_actests.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyATestCompanyCTest  $companyATestCompanyCTest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->toArray());
        // 首先
        $tests = [];
        foreach ($request->ac as $key => $value) {
            $temp = explode('_', $key);
            $temp2 = CompanyATestCompanyCTest::where('company_a_test_id', $temp[1])->where('company_c_test_id', $temp[0])->first()->id;
            array_push($tests, array('id' => $temp2, 'score' => $value));
        }


        app(CompanyATestCompanyCTest::class)->updateBatch($tests);
        return Reply::success('已更新完畢');

        // return $request->toArray();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyATestCompanyCTest  $companyATestCompanyCTest
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyATestCompanyCTest $companyATestCompanyCTest)
    {
        //
    }
}
