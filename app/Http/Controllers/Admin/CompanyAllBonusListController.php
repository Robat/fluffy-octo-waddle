<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompanyAllBonusList;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;

class CompanyAllBonusListController extends  AdminBaseController
{

    protected $messages = [];

    public function __construct()
    {
        parent::__construct();
        $this->frequenciesOpen = 'active open';
        $this->pageTitle = '獎金明細表';
        $this->frequenciesActive = 'active';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.all_bonus_list.index', $this->data);
    }

    public function ajax_index()
    {
        $result = CompanyAllBonusList::select(['id', 'name', 'bonus_setting_id', 'status', 'created_at', 'updated_at']);

        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showDetailEdit(' . $row->id . ')"><i class="fa fa-edit"></i> 編輯Detail</a>

                <a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-edit"></i> 編輯名稱</a>

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
     * @param  \App\Models\CompanyAllBonusList  $companyAllBonusList
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyAllBonusList $companyAllBonusList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyAllBonusList  $companyAllBonusList
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->all_bonus_list = CompanyAllBonusList::find($id);

        return View::make('admin.all_bonus_list.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyAllBonusList  $companyAllBonusList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyAllBonusList $companyAllBonusList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyAllBonusList  $companyAllBonusList
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyAllBonusList $companyAllBonusList)
    {
        //
    }
}
