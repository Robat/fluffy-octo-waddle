<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompanyCDiff;

use App\Classes\Reply;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\CompanyCValue\CreateRequest;
use App\Http\Requests\Admin\CompanyCValue\UpdateRequest;

class CompanyCDiffController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->cdiffOpen = 'active open';
        $this->pageTitle = 'CDiffs';
        $this->cdiffActive = 'active';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $this->bonus_calculation_id = $id;
        return View::make('admin.company_cdiffs.index', $this->data);
    }


    public function ajax_cdiffs($bonus_calculation_id)
    {
        $result = CompanyCDiff::where('bonus_calculation_id', $bonus_calculation_id);

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
    public function create($id)
    {
        $this->bonus_calculation_id = $id;

        return View::make('admin.company_cdiffs.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request, $id)
    {
        $data = $request->toArray();
        $data['bonus_calculation_id'] = $id;
        $data['frequency_id'] = admin()->company->frequency()->id;

        // dd($data);
        CompanyCDiff::create($data);

        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyCDiff  $companyCDiff
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyCDiff $companyCDiff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyCDiff  $companyCDiff
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $this->cdiff = CompanyCDiff::find($id);


        return View::make('admin.company_cdiffs.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyCDiff  $companyCDiff
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request,  $id)
    {

        $cvalue = CompanyCDiff::findOrFail($id);
        $data = $this->modifyRequest($request);


        $cvalue->update($data);


        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    private function modifyRequest($request)
    {

        $data = $request->all();
        // $data['mark'] = $request->name;
        // $data['numbering'] = $request->name;


        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyCDiff  $companyCDiff
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyCDiff $companyCDiff)
    {
        //
    }
}
