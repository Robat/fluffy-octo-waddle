<?php

namespace App\Http\Controllers\Admin;

use App\Models\PTest;
use App\Classes\Reply;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\PTest\CreateRequest;
use App\Http\Requests\Admin\PTest\UpdateRequest;

class PTestController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->atestOpen = 'active open';
        $this->pageTitle = 'Ptests';
        $this->atestActive = 'active';
    }

    public function index()
    {
        // dd(admin()->company->frequency()->id);
        return View::make('admin.ptests.index', $this->data);
    }



    public function ajax_ptests()
    {
        $result = PTest::select(['id', 'name', 'sort', 'created_at', 'updated_at']);

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
        return View::make('admin.ptests.create', $this->data);
    }


    public function store(CreateRequest $request)
    {

        PTest::create($request->toArray());

        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }

    public function edit($id)
    {
        $this->ptest = PTest::find($id);
        return View::make('admin.ptests.edit', $this->data);
    }

    public function update(UpdateRequest $request, $id)
    {
        $ptest = PTest::findOrFail($id);

        $ptest->update($request->toArray());

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }


    public function destroy($id)
    {
        if (Request::ajax()) {
            PTest::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
