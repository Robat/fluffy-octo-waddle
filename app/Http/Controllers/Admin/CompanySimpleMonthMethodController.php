<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Reply;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;

use App\Models\CompanySimpleMonthMethod;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;

use App\Http\Requests\Admin\SimpleMonth\CreateRequest;
use App\Http\Requests\Admin\SimpleMonth\UpdateRequest;

class CompanySimpleMonthMethodController extends AdminBaseController
{
    protected $messages = [];

    public function __construct()
    {
        parent::__construct();
        $this->frequenciesOpen = 'active open';
        $this->pageTitle = '簡單月數制';
        $this->frequenciesActive = 'active';
    }
    public function index()
    {
        return View::make('admin.simple_month.index', $this->data);
    }


    public function ajax_index()
    {
        $result = CompanySimpleMonthMethod::select(['id', 'simple_month', 'total', 'status', 'created_at', 'updated_at']);

        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('d-M-Y');
            })
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-edit"></i> Edit</a>

                                  <a href="javascript:void(0)" class="btn btn-danger" onclick="del(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-trash"></i> Delete</a>';
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
        return View::make('admin.simple_month.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $data = $request->toArray();
        CompanySimpleMonthMethod::create($data);

        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanySimpleMonthMethod  $companySimpleMonthMethod
     * @return \Illuminate\Http\Response
     */
    public function show(CompanySimpleMonthMethod $companySimpleMonthMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanySimpleMonthMethod  $companySimpleMonthMethod
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanySimpleMonthMethod $companySimpleMonthMethod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanySimpleMonthMethod  $companySimpleMonthMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanySimpleMonthMethod $companySimpleMonthMethod)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanySimpleMonthMethod  $companySimpleMonthMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanySimpleMonthMethod $companySimpleMonthMethod)
    {
        //
    }
}
