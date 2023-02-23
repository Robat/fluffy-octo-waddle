<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;

use App\Models\CompanyCValue;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\CompanyCValue\CreateRequest;
use App\Http\Requests\Admin\CompanyCValue\UpdateRequest;

class CompanyCValueController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->atestOpen = 'active open';
        $this->pageTitle = 'CValue';
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
        return View::make('admin.company_cvalues.index', $this->data);
    }



    public function ajax_cvalues($bonus_calculation_id)
    {
        $result = CompanyCValue::where('bonus_calculation_id', $bonus_calculation_id);

        return DataTables::of($result)
            ->addIndexColumn()
            ->editColumn('center', function ($row) {
                $color = [0 => '', 1 => 'checked'];

                return '<input type="checkbox" onclick="changeCenter(' . $row->id . ',\'' . $row->center . '\')"' . $color[$row->center] . '>';
            })
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->bonus_setting_id . '\')"><i class="fa fa-edit"></i> Edit</a>

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

        return View::make('admin.company_cvalues.create', $this->data);
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
        CompanyCValue::create($data);

        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyCValue  $companyCValue
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyCValue $companyCValue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyCValue  $companyCValue
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $this->cvalue = CompanyCValue::find($id);

        // dd($this->cvalue->id);

        return View::make('admin.company_cvalues.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyCValue  $companyCValue
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request,  $id)
    {

        $cvalue = CompanyCValue::findOrFail($id);
        $data = $this->modifyRequest($request);


        $cvalue->update($data);


        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyCValue  $companyCValue
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Request::ajax()) {
            CompanyCValue::destroy($id);
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
        $cvalue_default = CompanyCValue::Where('center', '<>', '0');
        $cvalue_default->update(
            ['center' => 0]
        );

        $cvalue = CompanyCValue::findOrFail($id);
        $center = ($cvalue->center == 1) ? 0 : 1;
        $cvalue->center = $center;
        $cvalue->save();

        return Reply::success('<strong> ' . '' . '</strong> updated successfully');
    }
}
