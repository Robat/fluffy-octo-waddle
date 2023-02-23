<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\Salary;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\Salary\CreateRequest;
use App\Http\Requests\Admin\Salary\UpdateRequest;

class SalariesController extends AdminBaseController
{
    protected $messages = [];
    /**
     * Constructor for the Salaries
     */

    public function __construct()
    {
        parent::__construct();
        $this->salariesOpen = 'active open';
        $this->pageTitle = '公司薪資結構';
        $this->salariesActive = 'active';
    }

    public function index()
    {
        return View::make('admin.salaries.index', $this->data);
    }

    public function ajaxSalaries()
    {
        //查詢薪資結構
        $result = Salary::select(['id', 'name', 'type', 'created_at', 'updated_at', 'status', 'is_generally', 'is_bonus', 'is_raise', 'is_promotion', 'is_performance']);


        return DataTables::of($result)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d');
            })
            ->addColumn('updated_at', function ($row) {
                return $row->updated_at->format('Y-m-d');
            })
            ->addColumn('action', function ($row) {
                $delBtn = '<a href="javascript:void(0)" class="btn btn-danger" onclick="del(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-trash"></i> 刪除</a>';
                if ($row->type == 'base') {
                    $delBtn = '';
                }

                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-edit"></i> 編輯</a>'
                    . $delBtn;
            })
            ->escapeColumns(['action'])
            ->make(true);
    }


    /**
     * Show the form for editing the specified grade.
     */
    public function create()
    {
        $this->salaries = Salary::all();
        return View::make('admin.salaries.create', $this->data);
    }


    /**
     * Store a newly created department in storage.
     */
    public function store(CreateRequest $request)
    {
        Salary::create($request->toArray());

        return Reply::success('<strong>{$request->deptName}</strong> successfully added to the Database');
    }


    public function edit($id)
    {
        $this->salaries = Salary::find($id);
        return View::make('admin.salaries.edit', $this->data);
    }

    /**
     * Update the specified department in storage.
     */
    public function update(UpdateRequest $request, $id)
    {

        $department = Salary::findOrFail($id);

        $department->update($request->toArray());
        // return redirect()->back()->with('success', 'your message,here');
        if ($request->updateType == 'default') {
            return Reply::success('<strong> ' . $request->deptName . '</strong> updated successfully');
        }
        // return redirect()->route('member.departments.index');
        return redirect('member/departments')->with('message', 'Department created successfully');
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy($id)
    {
        if (Request::ajax()) {
            if (Salary::findOrFail($id)->type != 'base') {
                Salary::destroy($id);
                return Reply::success('Deleted Successfully');
            } else {
                return Reply::error('無法刪除');
            }
        }
    }

    public function changeGenerally($id)
    {
        $salary = Salary::findOrFail($id);
        $is_generally = ($salary->is_generally == 1) ? 0 : 1;
        $salary->is_generally = $is_generally;
        $salary->save();

        return Reply::success('<strong> ' . '' . '</strong> updated successfully');
    }
    public function changeBonus($id)
    {
        $salary = Salary::findOrFail($id);
        $is_bonus = ($salary->is_bonus == 1) ? 0 : 1;
        $salary->is_bonus = $is_bonus;
        $salary->save();

        return Reply::success('<strong> ' . '' . '</strong> updated successfully');
    }
    public function changeRaise($id)
    {
        $salary = Salary::findOrFail($id);
        $is_raise = ($salary->is_raise == 1) ? 0 : 1;
        $salary->is_raise = $is_raise;
        $salary->save();

        return Reply::success('<strong> ' . '' . '</strong> updated successfully');
    }
    public function changePromotion($id)
    {
        $salary = Salary::findOrFail($id);
        $is_promotion = ($salary->is_promotion == 1) ? 0 : 1;
        $salary->is_promotion = $is_promotion;
        $salary->save();

        return Reply::success('<strong> ' . '' . '</strong> updated successfully');
    }
    public function changePerformance($id)
    {
        $salary = Salary::findOrFail($id);
        $is_performance = ($salary->is_performance == 1) ? 0 : 1;
        $salary->is_performance = $is_performance;
        $salary->save();

        return Reply::success('<strong> ' . '' . '</strong> updated successfully');
    }
}
