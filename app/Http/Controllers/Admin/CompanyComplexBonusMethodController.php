<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompanyComplexBonusMethod;
use App\Helpers\Reply;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\ComplexBonusMethod\CreateRequest;
use App\Http\Requests\Admin\ComplexBonusMethod\UpdateRequest;
use App\Models\CompanyComplexBonusMethodDetail;
use Yajra\DataTables\Facades\DataTables;



class CompanyComplexBonusMethodController extends AdminBaseController
{

    protected $messages = [];

    public function __construct()
    {
        parent::__construct();
        $this->frequenciesOpen = 'active open';
        $this->pageTitle = '複雜月數制';
        $this->frequenciesActive = 'active';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.complex_bonus.index', $this->data);
    }

    public function ajax_index()
    {
        $result = CompanyComplexBonusMethod::select(['id', 'name', 'status', 'created_at', 'updated_at']);

        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d');
            })
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showDetailEdit(' . $row->id . ')"><i class="fa fa-edit"></i> 編輯部門月數</a>

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
        return View::make('admin.complex_bonus.create', $this->data);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $complex_bonus = CompanyComplexBonusMethod::create($request->toArray());
        $departments = admin()->company->departments->pluck('id')->toArray(); //目前公司的部門
        $frequency_id = admin()->company->frequency()->id;

        $data = [];

        foreach ($departments as $department) {
            array_push($data, array(
                'frequency_id' => $frequency_id,
                'complex_id' => $complex_bonus->id,
                'department_id' => $department
            ));
        }

        CompanyComplexBonusMethodDetail::insert($data);

        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyComplexBonusMethod  $companyComplexBonusMethod
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyComplexBonusMethod $companyComplexBonusMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyComplexBonusMethod  $companyComplexBonusMethod
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->complex_bonus = CompanyComplexBonusMethod::find($id);

        return View::make('admin.complex_bonus.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyComplexBonusMethod  $companyComplexBonusMethod
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request,  $id)
    {
        $complex_bonus = CompanyComplexBonusMethod::findOrFail($id);

        $complex_bonus->name = $request->complex_bonus;
        $complex_bonus->save();

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyComplexBonusMethod  $companyComplexBonusMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Illuminate\Support\Facades\Request::ajax()) {
            CompanyComplexBonusMethod::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
