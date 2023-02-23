<?php

namespace App\Http\Controllers\Admin;

use App\Models\ADiff;
use App\Classes\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\ADiff\CreateRequest;
use App\Http\Requests\Admin\ADiff\UpdateRequest;

class ADiffController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->adiffOpen = 'active open';
        $this->pageTitle = '績效結果間距';
        $this->adiffActive = 'active';
    }


    public function index()
    {

        return View::make('admin.adiffs.index', $this->data);
    }

    public function ajax_adiffs()
    {
        $result = ADiff::select(['id', 'name', 'numbering', 'sort', 'status', 'created_at', 'updated_at']);


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
        $this->sort = ADiff::where('status', '1')->count();
        return View::make('admin.adiffs.create', $this->data);
    }

    public function store(CreateRequest $request)
    {

        ADiff::create($request->toArray());

        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }

    public function edit($id)
    {
        $this->adiff = ADiff::find($id);

        return View::make('admin.adiffs.edit', $this->data);
    }

    public function update(UpdateRequest $request, $id)
    {
        $adiff = ADiff::findOrFail($id);

        $sort_array = ADiff::select('id', 'sort')->where('id', '<>', $id)->where('status', '1')->orderBy('sort', 'asc')->pluck('id')->toArray();

        $inserted = $request->id;
        //重新
        array_splice($sort_array, $request->sort, 0, $id);

        $tests = [];
        for ($i = 0; $i < count($sort_array); $i++) {
            array_push($tests, array('id' => $sort_array[$i], 'sort' => $i));
        }


        $adiff->update($request->toArray());

        app(ADiff::class)->updateBatch($tests);

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    public function destroy($id)
    {
        if (Request::ajax()) {
            ADiff::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
