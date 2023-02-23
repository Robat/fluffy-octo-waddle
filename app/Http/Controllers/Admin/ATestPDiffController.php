<?php

namespace App\Http\Controllers\Admin;

use App\Models\ATest;
use App\Models\PDiff;
use App\Classes\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Models\ATestPDiff;
use App\Models\CompanyCenterPoint;

class ATestPDiffController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->atestOpen = 'active open';
        $this->pageTitle = 'P Value';
        $this->atestActive = 'active';
    }

    public function index()
    {
        $this->atests = ATest::with('p_diffs')->get();
        if (isset(admin()->company->frequency()->id)) {
            $this->pdiffs = PDiff::where('frequency_id', admin()->company->frequency()->id)->with('a_tests')->get();
        } else {
            $this->pdiffs = PDiff::with('a_tests')->get();
        }
        // dd($this->atests);
        return view('admin.ap.index', $this->data);
    }

    public function  edit()
    {
        $this->atests = ATest::with('p_diffs')->orderBy('sort', 'ASC')->get();
        $this->pdiffs = PDiff::where('frequency_id', admin()->company->frequency()->id)->with('a_tests')->orderBy('sort', 'ASC')->get();
        $this->point = CompanyCenterPoint::where('category', 'p')->where('status', '0')->first();

        return view('admin.ap.edit', $this->data);
    }

    public function update(Request $request)
    {
        // dd($request->ap);
        // 首先
        $tests = [];
        foreach ($request->ap as $key => $value) {
            $temp = explode('_', $key);
            $temp2 = ATestPDiff::where('a_test_id', $temp[1])->where('p_diff_id', $temp[0])->first()->id;
            array_push($tests, array('id' => $temp2, 'score' => $value));
        }


        app(ATestPDiff::class)->updateBatch($tests);
        return Reply::success('已更新完畢');
    }
}
