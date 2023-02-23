<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use App\Models\CompanyPerformance;
use Yajra\DataTables\Facades\DataTables;
use App\Classes\Reply;

use App\Http\Requests\Admin\ATest\CreateRequest;
use App\Http\Requests\Admin\ATest\UpdateRequest;

class CompanyPerformanceController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->company_performanceOpen = 'active open';
        $this->pageTitle = 'Performance';
        $this->company_performanceActive = 'active';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.company_performances.index', $this->data);
    }

    public function ajax_performances()
    {
        $result = CompanyPerformance::select(['id', 'title', 'rank_from', 'rank_to', 'sort', 'status', 'created_at', 'updated_at']);


        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-edit"></i> 編輯</a>

                                  <a href="javascript:void(0)" class="btn btn-danger" onclick="del(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-trash"></i> 刪除</a>';
            })
            ->escapeColumns(['action'])
            ->make(true);
    }

    public function create()
    {

        return View::make('admin.company_performances.create', $this->data);
    }

    public function store(CreateRequest $request)
    {

        $sort = CompanyPerformance::where('status', '1')->count();
        $sort = $sort * 2 + 1;
        $request->merge(["sort" => $sort]);
        CompanyPerformance::create($request->toArray());

        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }

    public function edit($id)
    {
        $this->company_performance = CompanyPerformance::find($id);
        return View::make('admin.company_performances.edit', $this->data);
    }



    public function update(UpdateRequest $request, $id)
    {
        $company_performance = CompanyPerformance::findOrFail($id);

        $company_performance->update($request->toArray());

        // app(ATest::class)->updateBatch($tests);

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    public function destroy($id)
    {
        if (Request::ajax()) {
            CompanyPerformance::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }

    private function arrayInsertAfterKey($array, $afterKey, $key, $value)
    {
        $pos   = array_search($afterKey, array_keys($array));

        return array_merge(
            array_slice($array, 0, $pos, $preserve_keys = true),
            array($key => $value),
            array_slice($array, $pos, $preserve_keys = true)
        );
    }
}
