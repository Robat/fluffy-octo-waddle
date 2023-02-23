<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\CompanyFrequency;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\Frequency\UpdateRequest;
use App\Http\Requests\Admin\Frequency\CreateRequest;

class CompanyFrequencyController extends AdminBaseController
{

    protected $messages = [];

    public function __construct()
    {
        parent::__construct();
        $this->frequenciesOpen = 'active open';
        $this->pageTitle = '年度期別';
        $this->frequenciesActive = 'active';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.frequencies.index', $this->data);
    }

    public function ajaxFrequencies()
    {
        $result = CompanyFrequency::select(['id', 'year_name', 'frequency_name', 'remarks', 'start_at', 'ends_at', 'status']);


        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-edit"></i> 編輯</a>

                                  <a href="javascript:void(0)" class="btn btn-danger" onclick="del(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-trash"></i> 刪除</a>';
            })
            ->escapeColumns(['action'])
            ->make(true);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $this->frequencies = CompanyFrequency::all();
        return View::make('admin.frequencies.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {

        CompanyFrequency::create($request->toArray());

        return Reply::success('<strong>{$request->deptName}</strong> 已新增到年度考核次數中');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyFrequency  $companyFrequency
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyFrequency $companyFrequency)
    {
        //
    }

    public function edit($id)
    {
        $this->frequency = CompanyFrequency::find($id);
        return View::make('admin.frequencies.edit', $this->data);
    }


    public function update(\Illuminate\Http\Request  $request, $id)
    {
        $frequency = CompanyFrequency::findOrFail($id);


        $frequency->update($request->toArray());



        return Reply::success('<strong>{$request->deptName}</strong> updated successfully');
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy($id)
    {
        if (Request::ajax()) {
            CompanyFrequency::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
