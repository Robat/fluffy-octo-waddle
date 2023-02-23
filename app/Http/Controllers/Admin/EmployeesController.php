<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\ATest;
use App\Models\Grade;
use App\Classes\Files;
use App\Classes\Reply;
use App\Http\Requests;

use App\Models\Salary;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeScore;
use App\Models\EmployeeSalary;
use App\Models\CompanyBonusList;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\Employee\EditRequest;
use App\Http\Requests\Admin\Employee\StoreRequest;
use App\Http\Requests\Admin\Employee\DeleteRequest;
use App\Http\Requests\Admin\Employee\UpdateRequest;

class EmployeesController extends AdminBaseController
{
    public static $MAX_EMPLOYEES = 100;

    /**
     * Constructor for the Employees
     */

    public function __construct()
    {
        parent::__construct();
        $this->employeesOpen = 'active open';
        $this->pageTitle = '員工列表';
        $this->peopleMenuActive = 'active';
    }

    public function index()
    {
        $this->employeesActive = 'active';


        $this->total = Employee::count();
        // Check logged in user can create employee according to this currecnt plan
        $this->checkCanCreateEmployee();

        return View::make('admin.employees.index', $this->data);
    }

    # Datatable ajax request
    public function ajax_employees()
    {
        $result = Employee::with(['designation', 'designation.grade'])->select('employees.id', 'employees.employeeID as employeeID', 'employees.fullName', 'employees.email', DB::raw('1 as work'), 'employees.status', 'employees.created_at', 'employees.designation_id')
            ->join('designations', 'employees.designation_id', '=', 'designations.id')
            ->join('grades', 'designations.grade_id', '=', 'grades.id')
            ->select('employees.*', 'grades.grade', 'designations.designation');


        return DataTables::of($result)
            ->addColumn('edit', function ($row) {
                // return $row->employeeID;
                $string = '<a class="btn purple btn-sm margin-bottom-5 btn-light" href="javascript:;" onclick="editUrl(\'' .
                    ($row->id) .
                    '\');"><i class="fa fa-edit"></i> ' .
                    '編輯' . '</a>
                        <a class="btn red btn-sm margin-bottom-5 btn-danger" href="javascript:;" onclick="del(\'' .
                    $row->id . '\',\'' . addslashes($row->full_name) .
                    '\')"><i class="fa fa-trash"></i> ' .
                    '刪除' . '</a>';

                return $string;
            })
            // ->editColumn('status', function ($row) {
            //     $color = ['active' => 'success', 'inactive' => 'danger'];

            //     return "<span id='status{$row->id}' class='label label-{$color[$row->status]}'>" .
            //         trans("core." . $row->status) . "</span>";
            // })
            ->editColumn('fullName', function ($row) {

                return $row->decryptToCollection()->fullName;
            })
            ->editColumn('designation', function ($row) {

                return $row->designation;
            })
            // ->editColumn('grade', function ($row) {

            //     return $row->designation->grade->grade;
            // })

            ->removeColumn("id")
            ->rawColumns(['edit', 'status'])
            ->make();
    }

    /**
     * Show the form for creating a new employee
     */
    public function create()
    {
        $this->employeesActive = 'active';
        $this->salaries = Salary::select('salaries.id as id', 'name', 'type')
            ->company($this->company_id)
            ->get();

        $this->department = Department::select('departments.id as id', 'deptName')
            ->company($this->company_id)
            ->manager(admin()->id)
            ->pluck('deptName', 'departments.id');

        $this->grade = Grade::select('grades.id as id', 'grade')
            ->company($this->company_id)
            ->manager(admin()->id)
            ->pluck('grade', 'id');

        $this->checkCanCreateEmployee();
        return View::make('admin.employees.create', $this->data);
    }

    public function store(StoreRequest $request)
    {
        // $this->checkCanCreateEmployee();

        $input = $request->all();
        $data = $request->all();
        // dd($data);
        // if (!$this->canCreateEmployee) {

        //     \App::abort("500");

        //     return false;
        // }

        DB::beginTransaction();
        try {
            $employee = Employee::create($input);
            foreach ($request->salary as $key => $value) {
                EmployeeSalary::create(
                    [
                        'employee_id' => $employee->id,
                        'salary_id' => $key,
                        'salary' => floatval($value)
                    ]
                );
            }

            // $employee_bonus = new CompanyBonusList();

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();
        return Reply::redirect(route('member.employees.index'), 'messages.employeeAddMessage');
    }


    /**
     * @param EditRequest $request
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(EditRequest $request, $id)
    {

        $this->pageTitle = trans('pages.employees.editTitle');

        $this->employeesActive = 'active';

        $this->department = Department::pluck('deptName', 'departments.id as id');
        $this->grade = Grade::pluck('grade', 'id');
        $this->employee = Employee::with('salaries.salaryName')->find($id);
        $company_salaries = Salary::select('salaries.id as id', 'name')
            ->company($this->company_id)
            ->pluck('name', 'id')
            ->toArray();

        foreach ($company_salaries as $key => $value) {
            EmployeeSalary::UpdateOrCreate(
                [
                    'employee_id' => $id,
                    'salary_id' => $key
                ]
            );
        }

        $this->salaries = $this->employee->salaries;

        $this->atests = ATest::pluck('name', 'id')->toArray();
        $this->score = EmployeeScore::where('frequency_id', admin()->company->frequency()->id)->where('employee_id', $id)->firstOrNew();


        // dd($this->employee->employee_salaries);
        // dd($this->employee->designation);
        $this->designation = Designation::find($this->employee->designation);

        return View::make('admin.employees.edit', $this->data);
    }

    public function update(UpdateRequest $request, $id)
    {
        // Check employee Company
        // dd($request->toArray());

        $employee = Employee::find($id);

        // if ($request->updateType == 'personalInfo') {

        $data = $request->all();
        if ($data['password'] == '') {
            unset($data['password']);
        }
        $employee->update($data);

        // 測試
        $employee_salaries = EmployeeSalary::with('salaryName')->where('employee_id', $employee->id)->pluck('salary_id', 'id')->toArray();

        $new_data = [];

        foreach ($employee_salaries as $key => $value) {
            array_push($new_data, array("id" => $key, "salary" => $request->salary[$key]));
        }

        // EmployeeScore::updateOrCreate(
        //     [
        //         'frequency_id' => admin()->company->frequency()->id,
        //         'employee_id' => $id
        //     ],
        //     [
        //         'score_id' => $request->score,
        //         'score' => ATest::find($request->score)->name
        //     ]
        // );




        // foreach ($request->salary as $key => $value) {
        //     EmployeeSalary::updated(
        //         [
        //             // 'employee_id' => $employee->id,
        //             'salary_id' => $key,
        //             'salary' => floatval($value)
        //         ]
        //     );
        // }

        // 批次更新
        app(EmployeeSalary::class)->updateBatch($new_data);

        return Reply::success('messages.personalUpdateSuccess');
        // }
    }

    /**
     * Remove the specified employee from storage.
     */

    public function destroy(DeleteRequest $request, $id)
    {

        Employee::destroy($id);

        return Reply::success("messages.successDelete");
    }

    public function checkCanCreateEmployee()
    {
        $currentTotalEmployee = Employee::manager(admin()->id)->count();
        // dd($currentTotalEmployee);
        $planTotalEmployee = admin()->company->plan->end_user_count;
        // dd($planTotalEmployee);
        if ($currentTotalEmployee < $planTotalEmployee) {
            $this->canCreateEmployee = true;
        } else {
            $this->canCreateEmployee = false;
        }
    }
}
