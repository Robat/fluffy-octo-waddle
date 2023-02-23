<?php

namespace App\Http\Controllers\Admin;

use App\Models\ADiff;
use App\Models\ATest;
use App\Models\PDiff;
use App\Models\CTest;
use App\Classes\Reply;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\PDiff\CreateRequest;
use App\Http\Requests\Admin\PDiff\UpdateRequest;

class PDiffController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pdiffOpen = 'active open';
        $this->pageTitle = 'PDiffs';
        $this->pdiffActive = 'active';
    }


    public function index()
    {

        return View::make('admin.pdiffs.index', $this->data);
    }


    public function ajax_pdiffs()
    {
        $result = PDiff::select(['id', 'name', 'numbering', 'sort', 'created_at', 'updated_at']);


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
        return View::make('admin.pdiffs.create', $this->data);
    }

    public function store(CreateRequest $request)
    {

        $p_diff = PDiff::create($request->toArray());

        $PDiffCount = PDiff::where('frequency_id', admin()->company->frequency()->id)->count();
        $p_diff->sort = $PDiffCount;
        $p_diff->save();

        $atest_all = ATest::where('frequency_id', admin()->company->frequency()->id)->pluck('sort', 'id')->toArray();


        foreach ($atest_all as $key => $value) {
            $atest_all[$key] = array(
                'frequency_id' => admin()->company->frequency()->id,
                'a_sort' => $value,
            );
        }

        $pdiffs = PDiff::where('frequency_id', admin()->company->frequency()->id)->get();

        foreach ($pdiffs as $pdiff) {
            $pdiff->a_tests()->sync($atest_all);
        }


        $pdiff_all = PDiff::where('frequency_id', admin()->company->frequency()->id)->pluck('sort', 'id')->toArray();
        foreach ($pdiff_all as $key => $value) {
            $pdiff_all[$key] = array(
                'frequency_id' => admin()->company->frequency()->id,
                'p_sort' => $value,
            );
        }

        $atests = ATest::where('frequency_id', admin()->company->frequency()->id)->get();

        foreach ($atests as  $value) {
            $value->p_diffs()->sync($pdiff_all);
        }


        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }

    public function edit($id)
    {
        $this->pdiff = PDiff::find($id);
        return View::make('admin.pdiffs.edit', $this->data);
    }

    public function update(UpdateRequest $request, $id)
    {
        $pdiff = PDiff::findOrFail($id);

        $pdiff->update($request->toArray());

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    public function destroy($id)
    {
        if (Request::ajax()) {
            PDiff::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
