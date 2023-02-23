<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompanyPDiff;
use App\Classes\Reply;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\CompanyCValue\CreateRequest;
use App\Http\Requests\Admin\CompanyCValue\UpdateRequest;

class CompanyPDiffController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->Open = 'active open';
        $this->pageTitle = 'PDiffs';
        $this->Active = 'active';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $this->bonus_calculation_id = $id;
        return View::make('admin.company_pdiffs.index', $this->data);
    }

    public function ajax_pdiffs($bonus_calculation_id)
    {
        $result = CompanyPDiff::where('bonus_calculation_id', $bonus_calculation_id);

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

        return View::make('admin.company_pdiffs.create', $this->data);
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
        CompanyPDiff::create($data);

        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyPDiff  $companyPDiff
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyPDiff $companyPDiff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyPDiff  $companyPDiff
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->company_pdiff = CompanyPDiff::find($id);
        return View::make('admin.company_pdiffs.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyPDiff  $companyPDiff
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {

        $company_pdiff = CompanyPDiff::findOrFail($id);
        $data = $this->modifyRequest($request);
        $company_pdiff->update($data);

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyPDiff  $companyPDiff
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Request::ajax()) {
            CompanyPDiff::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }


    private function modifyRequest($request)
    {
        $data = $request->all();


        return $data;
    }
}
