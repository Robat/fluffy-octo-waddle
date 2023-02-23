<?php

namespace App\Http\Controllers\Admin;

use App\Models\ATest;
use App\Classes\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\ATest\CreateRequest;
use App\Http\Requests\Admin\ATest\UpdateRequest;

class ATestController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->atestOpen = 'active open';
        $this->pageTitle = 'Atests';
        $this->atestActive = 'active';
    }


    public function index()
    {
        return View::make('admin.atests.index', $this->data);
    }

    public function create()
    {

        return View::make('admin.atests.create', $this->data);
    }

    public function store(CreateRequest $request)
    {

        $sort = ATest::where('status', '1')->count();
        $sort = $sort * 2 + 1;
        $request->merge(["sort" => $sort]);
        ATest::create($request->toArray());

        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }

    public function ajax_atests()
    {
        $result = ATest::select(['id', 'name', 'rank_from', 'rank_to', 'numbering', 'sort', 'status', 'created_at', 'updated_at']);


        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-edit"></i> Edit</a>

                                  <a href="javascript:void(0)" class="btn btn-danger" onclick="del(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-trash"></i> Delete</a>';
            })
            ->escapeColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        $this->atest = ATest::find($id);
        return View::make('admin.atests.edit', $this->data);
    }



    public function update(UpdateRequest $request, $id)
    {
        $atest = ATest::findOrFail($id);
        // dd($request->toArray());

        // 1125 不要先做重新排序
        // $sort_array = ATest::select('id', 'sort')->where('id', '<>', $id)->where('status', '1')->orderBy('sort', 'asc')->pluck('id')->toArray();

        // $inserted = $request->id;

        // //重新
        // array_splice($sort_array, $request->sort, 0, $id);


        // $tests = [];
        // for ($i = 0; $i < count($sort_array); $i++) {
        //     array_push($tests, array('id' => $sort_array[$i], 'sort' => $i));
        // }
        // dd($tests);

        $atest->update($request->toArray());

        // app(ATest::class)->updateBatch($tests);

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    public function destroy($id)
    {
        if (Request::ajax()) {
            ATest::destroy($id);
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
