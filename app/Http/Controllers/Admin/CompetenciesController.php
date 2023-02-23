<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\Competency;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\Competency\CreateRequest;
use App\Http\Requests\Admin\Competency\UpdateRequest;

class CompetenciesController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->employeesOpen = 'active open';
        $this->pageTitle = '職務';
        $this->employeesActive = 'active';
    }

    public function index()
    {
        return View::make('admin.competencies.index', $this->data);
    }

    public function ajaxCompetencies()
    {
        $result = Competency::select(['id', 'competency', 'created_at', 'updated_at']);


        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->competency . '\')"><i class="fa fa-edit"></i> 編輯</a>

                <a class="btn btn-danger" onclick="del(' . $row->id . ',\'' . $row->competency . '\')"><i class="fa fa-trash"></i> 刪除</a>';
            })
            ->escapeColumns(['action'])
            ->make(true);
    }


    /**
     * Show the form for editing the specified department.
     */
    public function create()
    {
        return View::make('admin.competencies.create', $this->data);
    }


    /**
     * Store a newly created department in storage.
     */
    public function store(CreateRequest $request)
    {
        // $compe = new Competency();
        // $compe->competency = $request->competency;
        // $compe->save();
        Competency::create($request->toArray());
        return Reply::success('<strong>{$request->competency}</strong> successfully added to the Database');
    }



    public function edit($id)
    {
        $this->competency = Competency::find($id);
        return View::make('admin.competencies.edit', $this->data);
    }

    /**
     * Update the specified competency in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        $competency = Competency::findOrFail($id);

        $competency->update([
            'competency' => $request->competency
        ]);


        return Reply::success('<strong>{$request->competency}</strong> updated successfully');
    }
    /**
     * Remove the specified competency from storage.
     */
    public function destroy($id)
    {
        if (Request::ajax()) {
            Competency::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
