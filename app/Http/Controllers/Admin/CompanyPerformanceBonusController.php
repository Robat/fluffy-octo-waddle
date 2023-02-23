<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompanyPerformanceBonus;
use App\Helpers\Reply;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\AdminBaseController;
use Yajra\DataTables\Facades\DataTables;


class CompanyPerformanceBonusController extends AdminBaseController
{
    protected $messages = [];

    public function __construct()
    {
        parent::__construct();
        $this->frequenciesOpen = 'active open';
        $this->pageTitle = '績效結果法';
        $this->frequenciesActive = 'active';
    }

    public function index($id)
    {
        $this->performance_id = $id;
        return View::make('admin.performance_bonus.index', $this->data);
    }


    public function ajax_index($performance_id)
    {
        $result = CompanyPerformanceBonus::select(['id', 'name', 'numbering', 'sort', 'status', 'created_at', 'updated_at', 'calculation_id'])->where('calculation_id', $performance_id);

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
     * @param  \App\Models\CompanyPerformanceBonus  $companyPerformanceBonus
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyPerformanceBonus $companyPerformanceBonus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyPerformanceBonus  $companyPerformanceBonus
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->performance = CompanyPerformanceBonus::find($id);
        return View::make('admin.performance_bonus.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyPerformanceBonus  $companyPerformanceBonus
     * @return \Illuminate\Http\Response
     */
    public function update(\Illuminate\Http\Request  $request, $id)
    {
        $performance = CompanyPerformanceBonus::findOrFail($id);

        $performance->update([
            'name' => $request->name,
        ]);


        return Reply::success('<strong>{$request->deptName}</strong> updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyPerformanceBonus  $companyPerformanceBonus
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyPerformanceBonus $companyPerformanceBonus)
    {
        //
    }
}
