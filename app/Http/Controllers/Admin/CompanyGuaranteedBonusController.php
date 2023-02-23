<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use Illuminate\Support\Facades\View;
use App\Models\CompanyGuaranteedBonus;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\Guaranteed\CreateRequest;
use App\Http\Requests\Admin\Guaranteed\UpdateRequest;


class CompanyGuaranteedBonusController extends  AdminBaseController
{
    protected $messages = [];

    public function __construct()
    {
        parent::__construct();
        $this->Open = 'active open';
        $this->pageTitle = '保證獎金';
        $this->Active = 'active';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.guaranteed_bonus.index', $this->data);
    }


    public function ajax_index()
    {
        $result = CompanyGuaranteedBonus::select(['id',  'status', 'name', 'fixed_amount']);

        return DataTables::of($result)
            ->addIndexColumn()
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
        return View::make('admin.guaranteed_bonus.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        CompanyGuaranteedBonus::create($request->toArray());

        return Reply::success('<strong></strong> successfully added to the Database');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyGuaranteedBonus  $companyGuaranteedBonus
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyGuaranteedBonus $companyGuaranteedBonus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyGuaranteedBonus  $companyGuaranteedBonus
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->guaranteed = CompanyGuaranteedBonus::find($id);

        return View::make('admin.guaranteed_bonus.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyGuaranteedBonus  $companyGuaranteedBonus
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $guaranteed = CompanyGuaranteedBonus::findOrFail($id);
        $guaranteed->update($request->toArray());

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyGuaranteedBonus  $companyGuaranteedBonus
     * @return \Illuminate\Http\Response
     */
    // public function destroy(CompanyGuaranteedBonus $companyGuaranteedBonus)
    // {
    //     //
    // }
    public function destroy($id)
    {
        if (Request::ajax()) {
            CompanyGuaranteedBonus::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
