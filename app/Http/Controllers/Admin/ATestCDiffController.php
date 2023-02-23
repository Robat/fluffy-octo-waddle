<?php

namespace App\Http\Controllers\Admin;

use App\Models\ATest;
use App\Models\CDiff;
use App\Classes\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Models\ATestCDiff;
use App\Models\CompanyCenterPoint;

class ATestCDiffController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->atestOpen = 'active open';
        $this->pageTitle = 'CR Value';
        $this->atestActive = 'active';
    }

    public function index()
    {
        $this->atests = ATest::with('c_diffs')->get();
        if (isset(admin()->company->frequency()->id)) {
            $this->cdiffs = CDiff::where('frequency_id', admin()->company->frequency()->id)->with('a_tests')->get();
        } else {
            $this->cdiffs = CDiff::with('a_tests')->get();
        }
        // dd($this->atests);
        return view('admin.ac.index', $this->data);
    }

    public function  edit()
    {
        $this->atests = ATest::with('c_diffs')->orderBy('sort', 'ASC')->get();
        $this->cdiffs = CDiff::where('frequency_id', admin()->company->frequency()->id)->with('a_tests')->orderBy('sort', 'ASC')->get();
        $this->point = CompanyCenterPoint::where('category', 'p')->where('status', '0')->first();
        // dd($this->atests);
        return view('admin.ac.edit', $this->data);
    }

    public function update(Request $request)
    {
        // dd($request->ac)
        // 首先
        $tests = [];
        foreach ($request->ac as $key => $value) {
            $temp = explode('_', $key);
            $temp2 = ATestCDiff::where('a_test_id', $temp[1])->where('c_diff_id', $temp[0])->first()->id;
            array_push($tests, array('id' => $temp2, 'score' => $value));
        }


        app(ATestCDiff::class)->updateBatch($tests);
        return Reply::success('已更新完畢');
    }
}
