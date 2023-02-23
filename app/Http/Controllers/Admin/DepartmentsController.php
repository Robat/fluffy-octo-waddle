<?php

namespace App\Http\Controllers\Admin;


use App\Classes\Reply;
use App\Models\Department;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\Department\CreateRequest;
use App\Http\Requests\Admin\Department\UpdateRequest;
use App\Models\Employee;

class DepartmentsController extends AdminBaseController
{

    protected $messages = [];
    /**
     * Constructor for the Departments
     */

    public function __construct()
    {
        parent::__construct();
        $this->departmentsOpen = 'active open';
        $this->pageTitle = '部門';
        $this->departmentsActive = 'active';
    }

    public function index()
    {
        return View::make('admin.departments.index', $this->data);
    }

    public function ajaxDepartments()
    {
        $result = Department::with('members')->select('id', 'deptName', 'created_at', 'updated_at')
            ->selectSub(function ($query) {
                $query->selectRaw('count(*)')
                    ->from('employees')
                    ->whereRaw('employees.department_id = departments.id')
                    ->groupBy('employees.department_id');
            }, 'employee_count');

        return DataTables::of($result)
            ->addIndexColumn()
            ->editColumn('deptName', function ($row) {
                $string = '';
                if (count($row->members) > 0) {
                    $string = $row->deptName . ' <span class="label label-success">' . count($row->members) . ' 名成員</span>';
                } else {
                    $string = $row->deptName . ' <span class="label label-success">0 名成員</span>';
                }

                return $string;
            })
            ->addColumn('employee', function ($row) {
                $string = '';
                $i = 1;

                foreach ($row->members as $member) {
                    $string .= '<img data-toggle="tooltip" data-original-title=" Mrs. Etha Tromp" src="https://demo-saas.worksuite.biz/img/default-profile-3.png"
                    alt="user" class="img-circle" width="25" height="25">';
                    $i = $i + 1;
                };

                return $string;
            })
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light btn-xs"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->deptName . '\')"><i class="fa fa-edit"></i> 編輯</a>

                                      <a href="javascript:void(0)" class="btn btn-danger  btn-xs" onclick="del(' . $row->id . ',\'' . $row->deptName . '\')"><i class="fa fa-trash"></i> 刪除</a>';
            })
            ->escapeColumns(['action', 'employee'])
            ->rawColumns(['employee', 'action'])
            ->make(true);
    }



    /**
     * Show the form for editing the specified department.
     */
    public function create()
    {
        return View::make('admin.departments.create', $this->data);
    }


    /**
     * Store a newly created department in storage.
     */
    public function store(CreateRequest $request)
    {
        Department::create($request->toArray());

        return Reply::success('<strong>{$request->deptName}</strong> successfully added to the Database');
    }



    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Update the specified department in storage.
     */
    public function update(UpdateRequest $request, $id)
    {

        $department = Department::findOrFail($id);

        $department->update($request->toArray());
        // return redirect()->back()->with('success', 'your message,here');
        if ($request->updateType == 'default') {
            return Reply::success('<strong> ' . $request->deptName . '</strong> updated successfully');
        }
        // return redirect()->route('member.departments.index');
        return redirect('member/departments')->with('message', 'Department created successfully');
    }

    //測試用
    public function ajaxUpdate(UpdateRequest $request, $id)
    {

        $department = Department::findOrFail($id);

        $department->update($request->toArray());
        // return Reply::redirect(route('member.departments.index'));
        return Reply::success('<strong> ' . $request->deptName . '</strong> updated successfully');
    }
    /**
     * Remove the specified department from storage.
     */
    public function destroy($id)
    {
        if (Request::ajax()) {
            Department::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }
}
