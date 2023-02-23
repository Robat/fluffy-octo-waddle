<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Reply;
use App\Models\CompanySpecialBonus;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;

use App\Models\CompanySpecialBonusDetail;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\SpecialBonus\CreateRequest;

class CompanySpecialBonusController extends AdminBaseController
{
    protected $messages = [];

    public function __construct()
    {
        parent::__construct();
        $this->frequenciesOpen = 'active open';
        $this->pageTitle = '事業部特別獎金';
        $this->frequenciesActive = 'active';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        return View::make('admin.special_bonus.index', $this->data);
    }



    public function ajax_index()
    {
        $result = CompanySpecialBonus::select(['id', 'name', 'status', 'created_at', 'updated_at']);

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
        return View::make('admin.special_bonus.create', $this->data);
    }

    public function store(CreateRequest $request)
    {
        $special_bonus = CompanySpecialBonus::create($request->toArray());

        $departments = admin()->company->departments->pluck('id')->toArray(); //目前公司的部門
        $frequency_id = admin()->company->frequency()->id;

        $data = [];

        foreach ($departments as $department) {
            array_push($data, array(
                'frequency_id' => $frequency_id,
                'special_id' => $special_bonus->id,
                'department_id' => $department
            ));
        }

        CompanySpecialBonusDetail::insert($data);

        return Reply::success('<strong></strong> successfully added to the Database');
    }


    public function edit(Request $request, $id)
    {
        $this->special = CompanySpecialBonus::find($id);
        return View::make('admin.special_bonus.edit', $this->data);
    }

    public function update(\Illuminate\Http\Request  $request, $id)
    {
        $special = CompanySpecialBonus::findOrFail($id);

        $special->update([
            'name' => $request->name,
        ]);


        return Reply::success('<strong>{$request->deptName}</strong> updated successfully');
    }

    public function destroy($id)
    {
        if (Request::ajax()) {
            CompanySpecialBonus::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
