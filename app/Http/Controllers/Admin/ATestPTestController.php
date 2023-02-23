<?php

namespace App\Http\Controllers\Admin;

use App\Models\ATest;
use App\Models\PTest;
use App\Classes\Reply;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Models\ATestPTest;
use App\Models\CompanyCenterPoint;


class ATestPTestController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->salariesOpen = 'active open';
        $this->pageTitle = 'AP';
        $this->totalAmountActive = 'active';
    }

    public function index()
    {

        $this->atests = ATest::with('p_tests')->get();
        if (isset(admin()->company->frequency()->id)) {
            $this->ptests = PTest::where('frequency_id', admin()->company->frequency()->id)->with('a_tests')->get();
        } else {
            $this->ptests = PTest::with('a_tests')->get();
        }
        return view('admin.aptests.index', $this->data);
    }

    public function  edit()
    {
        $this->atests = ATest::with('p_diffs')->orderBy('sort', 'ASC')->get();
        $this->ptests = PTest::where('frequency_id', admin()->company->frequency()->id)->with('a_tests')->orderBy('sort', 'ASC')->get();
        $this->point = CompanyCenterPoint::where('category', 'p')->where('status', '0')->first();

        return view('admin.aptests.edit', $this->data);
    }

    public function update(Request $request)
    {

        $tests = [];
        foreach ($request->ap as $key => $value) {
            $temp = explode('_', $key);
            $temp2 = ATestPTest::where('a_test_id', $temp[1])->where('p_test_id', $temp[0])->first()->id;
            array_push($tests, array('id' => $temp2, 'score' => $value));
        }


        app(ATestPTest::class)->updateBatch($tests);
        return Reply::success('已更新完畢');
    }
}
