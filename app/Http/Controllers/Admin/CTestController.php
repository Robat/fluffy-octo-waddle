<?php

namespace App\Http\Controllers\Admin;

use App\Models\ATest;
use App\Models\CTest;
use App\Classes\Reply;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\CTest\CreateRequest;
use App\Http\Requests\Admin\CTest\UpdateRequest;

class CTestController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->atestOpen = 'active open';
        $this->pageTitle = 'Ctests';
        $this->atestActive = 'active';
    }

    public function index()
    {
        // dd(admin()->company->frequency()->id);
        return View::make('admin.ctests.index', $this->data);
    }



    public function ajax_ctests()
    {
        $result = CTest::select(['id', 'name', 'sort', 'created_at', 'updated_at']);

        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-edit"></i> Edit</a>

                                  <a href="javascript:void(0)" class="btn btn-danger" onclick="del(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-trash"></i> Delete</a>';
            })
            ->escapeColumns(['action'])
            ->make(true);
    }


    public function create()
    {
        return View::make('admin.ctests.create', $this->data);
    }


    public function store(CreateRequest $request)
    {

        $c_test = CTest::create($request->toArray());

        $CTestCount =  CTest::where('frequency_id', admin()->company->frequency()->id)->count();
        $c_test->sort = $CTestCount;
        $c_test->save();

        $atest_all = ATest::where('frequency_id', admin()->company->frequency()->id)->pluck('sort', 'id')->toArray();


        foreach ($atest_all as $key => $value) {
            $atest_all[$key] = array(
                'frequency_id' => admin()->company->frequency()->id,
                'a_sort' => $value,
            );
        }

        $ctests = CTest::where('frequency_id', admin()->company->frequency()->id)->get();

        foreach ($ctests as $ctest) {
            $ctest->a_tests()->sync($atest_all);
        }


        $ctest_all = CTest::where('frequency_id', admin()->company->frequency()->id)->pluck('sort', 'id')->toArray();
        foreach ($ctest_all as $key => $value) {
            $ctest_all[$key] = array(
                'frequency_id' => admin()->company->frequency()->id,
                'c_sort' => $value,
            );
        }

        $atests = ATest::where('frequency_id', admin()->company->frequency()->id)->get();

        foreach ($atests as  $value) {
            $value->c_tests()->sync($ctest_all);
        }


        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }

    public function edit($id)
    {
        $this->ctest = CTest::find($id);
        return View::make('admin.ctests.edit', $this->data);
    }

    public function update(UpdateRequest $request, $id)
    {
        $ctest = CTest::findOrFail($id);

        $ctest->update($request->toArray());

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }


    public function destroy($id)
    {
        if (Request::ajax()) {
            CTest::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
