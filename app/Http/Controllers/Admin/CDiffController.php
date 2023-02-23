<?php

namespace App\Http\Controllers\Admin;

use App\Models\ADiff;
use App\Models\ATest;
use App\Models\CDiff;
use App\Models\CTest;
use App\Classes\Reply;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\CDiff\CreateRequest;
use App\Http\Requests\Admin\CDiff\UpdateRequest;

class CDiffController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->cdiffOpen = 'active open';
        $this->pageTitle = 'CDiffs';
        $this->cdiffActive = 'active';
    }


    public function index()
    {

        return View::make('admin.cdiffs.index', $this->data);
    }


    public function ajax_cdiffs()
    {
        $result = CDiff::select(['id', 'name', 'numbering', 'sort', 'created_at', 'updated_at']);


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
        return View::make('admin.cdiffs.create', $this->data);
    }

    public function store(CreateRequest $request)
    {

        $c_diff = CDiff::create($request->toArray());

        $CDiffCount = CDiff::where('frequency_id', admin()->company->frequency()->id)->count();
        $c_diff->sort = $CDiffCount;
        $c_diff->save();

        $atest_all = ATest::where('frequency_id', admin()->company->frequency()->id)->pluck('sort', 'id')->toArray();


        foreach ($atest_all as $key => $value) {
            $atest_all[$key] = array(
                'frequency_id' => admin()->company->frequency()->id,
                'a_sort' => $value,
            );
        }

        $cdiffs = CDiff::where('frequency_id', admin()->company->frequency()->id)->get();

        foreach ($cdiffs as $cdiff) {
            $cdiff->a_tests()->sync($atest_all);
        }


        $cdiff_all = CDiff::where('frequency_id', admin()->company->frequency()->id)->pluck('sort', 'id')->toArray();
        foreach ($cdiff_all as $key => $value) {
            $cdiff_all[$key] = array(
                'frequency_id' => admin()->company->frequency()->id,
                'c_sort' => $value,
            );
        }

        $atests = ATest::where('frequency_id', admin()->company->frequency()->id)->get();

        foreach ($atests as  $value) {
            $value->c_diffs()->sync($cdiff_all);
        }


        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }

    public function edit($id)
    {
        $this->cdiff = CDiff::find($id);
        return View::make('admin.cdiffs.edit', $this->data);
    }

    public function update(UpdateRequest $request, $id)
    {
        $cdiff = CDiff::findOrFail($id);

        $cdiff->update($request->toArray());

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    public function destroy($id)
    {
        if (Request::ajax()) {
            CDiff::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
