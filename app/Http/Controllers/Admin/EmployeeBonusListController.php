<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmployeeBonusList;

use App\Classes\Reply;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;

class EmployeeBonusListController extends AdminBaseController
{

    protected $messages = [];

    public function __construct()
    {
        parent::__construct();
        $this->frequenciesOpen = 'active open';
        $this->pageTitle = '員工獎金列表';
        $this->frequenciesActive = 'active';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.employee_bonus_list.index', $this->data);
    }

    public function ajax_index()
    {
        $result = EmployeeBonusList::select(['id', 'name', 'bonus_setting_id', 'status', 'created_at', 'updated_at']);

        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showDetailEdit(' . $row->id . ')"><i class="fa fa-edit"></i> 編輯Detail</a>

                <a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-edit"></i> Edit</a>

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeBonusList  $employeeBonusList
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeBonusList $employeeBonusList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeBonusList  $employeeBonusList
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeBonusList $employeeBonusList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeBonusList  $employeeBonusList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeBonusList $employeeBonusList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeBonusList  $employeeBonusList
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeBonusList $employeeBonusList)
    {
        //
    }
}
