<?php

namespace App\Http\Controllers\Admin;

use App\Models\ATest;
use App\Models\CTest;
use App\Classes\Reply;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Models\ATestCTest;
use App\Models\CompanyCenterPoint;


class ATestCTestController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->salariesOpen = 'active open';
        $this->pageTitle = 'AC';
        $this->totalAmountActive = 'active';
    }

    public function index()
    {
        // $ATest = ATest::with('c_tests')->first();
        // $CTest = $ATest->c_tests->first()->pivot->score;

        $this->atests = ATest::with('c_tests')->get();
        if (isset(admin()->company->frequency()->id)) {
            $this->ctests = CTest::where('frequency_id', admin()->company->frequency()->id)->with('a_tests')->get();
        } else {
            $this->ctests = CTest::with('a_tests')->get();
        }
        return view('admin.actests.index', $this->data);
    }

    public function edit()
    {
        $this->atests = ATest::with('c_diffs')->orderBy('sort', 'ASC')->get();
        $this->ctests = CTest::where('frequency_id', admin()->company->frequency()->id)->with('a_tests')->orderBy('sort', 'ASC')->get();
        $this->point = CompanyCenterPoint::where('category', 'c')->where('status', '0')->first();

        return view('admin.actests.edit', $this->data);
    }

    public function update(Request $request)
    {

        // 首先
        $tests = [];
        foreach ($request->ac as $key => $value) {
            $temp = explode('_', $key);
            $temp2 = ATestCTest::where('a_test_id', $temp[1])->where('c_test_id', $temp[0])->first()->id;
            array_push($tests, array('id' => $temp2, 'score' => $value));
        }


        app(ATestCTest::class)->updateBatch($tests);
        return Reply::success('已更新完畢');
    }
}
