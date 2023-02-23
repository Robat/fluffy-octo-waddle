<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;

use App\Models\CompanyPValue;
use App\Classes\Reply;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\PValue\CreateRequest;
use App\Http\Requests\Admin\PValue\UpdateRequest;

class CompanyPValueController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->atestOpen = 'active open';
        $this->pageTitle = 'PValue';
        $this->atestActive = 'active';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $this->bonus_calculation_id = $id;
        return View::make('admin.company_pvalues.index', $this->data);
    }



    public function ajax_pvalues($bonus_calculation_id)
    {
        $result = CompanyPValue::where('bonus_calculation_id', $bonus_calculation_id);

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
        return View::make('admin.company_pvalues.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request, $id)
    {
        $data = $this->modifyRequest($request);
        $data['bonus_calculation_id'] = $id;
        $data['frequency_id'] = admin()->company->frequency()->id;

        CompanyPValue::create($data);

        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }


    public function show(CompanyPValue $companyPValue)
    {
        //
    }


    public function edit($id)
    {
        $this->company_pvalue = CompanyPValue::find($id);
        return View::make('admin.company_pvalues.edit', $this->data);
    }


    public function update(UpdateRequest $request, $id)
    {

        $company_pvalue = CompanyPValue::findOrFail($id);
        $data = $this->modifyRequest($request);
        $company_pvalue->update($data);

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }




    public function destroy($id)
    {
        if (Request::ajax()) {
            CompanyPValue::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }


    private function modifyRequest($request)
    {
        $data = $request->all();
        $data['mark'] = $request->name;
        $data['numbering'] = $request->name;

        return $data;
    }


    public function changeCenter($id)
    {

        $pvalue = CompanyPValue::findOrFail($id);
        $center = ($pvalue->center == 1) ? 0 : 1;
        $pvalue->center = $center;
        $pvalue->save();

        return Reply::success('<strong> ' . '' . '</strong> updated successfully');
    }
}
