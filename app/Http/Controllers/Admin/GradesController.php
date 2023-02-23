<?php

namespace App\Http\Controllers\Admin;

use App\Models\Grade;
use App\Classes\Reply;
use App\Models\Employee;
use App\Models\Competency;
use App\Models\Designation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\Grade\CreateRequest;
use App\Http\Requests\Admin\Grade\UpdateRequest;


class GradesController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->gradesOpen = 'active open';
        $this->pageTitle = '職等';
        $this->gradesActive = 'active';
    }
    public function index()
    {

        // dd($this->company_id);
        $this->grades = Grade::select('grades.id as id', 'grade')
            ->company($this->company_id)
            ->get();

        $employeeCount = [];

        foreach ($this->grades as $grade) {
            $employeeCount[$grade->id] = Employee::join('designations', 'employees.designation_id', '=', 'designations.id')
                ->join('grades', 'designations.grade_id', '=', 'grades.id')
                ->where('grades.id', '=', $grade->id)
                ->where('grades.company_id', '=', $this->company_id)
                ->selectRaw('COUNT(employees.id) as count')
                ->value('count');
        }

        $this->employeeCount = $employeeCount;

        $this->data = [
            'pageTitle' => '職等設定',
            'grades' => $this->grades,
            'employeeCount' => $this->employeeCount,
            'data' => ['designation' => ''],
        ];

        return view('admin.grades.index', $this->data);
    }


    public function ajaxGrades()
    {
        $result = Grade::select('grades.id', 'grades.competencyID', 'competencies.competency', 'grades.grade', 'grades.designation', 'grades.created_at', 'grades.updated_at')
            ->selectSub(function ($query) {
                $query->from('employees')
                    ->join('designations', 'employees.designation_id', '=', 'designations.id')
                    ->whereColumn('designations.grade_id', 'grades.id')
                    ->selectRaw('COUNT(employees.id)');
            }, 'count')
            ->where('grades.company_id', '=', $this->company_id)
            ->join('competencies', 'grades.competencyID', '=', 'competencies.id')
            // ->orderBy('grades.id')
            ->orderByRaw('CAST(grades.id AS UNSIGNED)')
            ->distinct('grades.id')
            ->get();

        $designations = [];
        $designationList = Designation::select('designations.grade_id', 'designations.designation')
            ->whereIn('designations.grade_id', $result->pluck('id'))
            ->get();
        foreach ($designationList as $designation) {
            $designations[$designation->grade_id][] = $designation;
        }

        $data = [];
        foreach ($result as $row) {
            $rowArray = $row->toArray();
            $rowArray['action'] = '<a class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->grade . '\')"><i class="fa fa-edit"></i> 編輯</a><a class="btn btn-danger" onclick="del(' . $row->id . ',\'' . $row->grade . '\')"><i class="fa fa-trash"></i> 刪除</a>';
            $rowArray['employees'] = $row->count;
            $rowArray['designation'] = '';
            if (isset($designations[$row->id])) {
                foreach ($designations[$row->id] as $designation) {
                    $rowArray['designation'] .= '<li>' . $designation->designation . '</li>';
                }
            }
            $data[] = $rowArray;
        }

        return DataTables::of($result)
            ->addIndexColumn()
            ->editColumn('designation', function ($row) use ($designations) {
                $string = '';
                if (isset($designations[$row->id])) {
                    foreach ($designations[$row->id] as $designation) {
                        $string .= '<li>' . $designation->designation . '</li>';
                    }
                }
                $string = '<ul>' . $string . '<ul>';
                return $string;
            })
            ->addColumn('action', function ($row) {
                return '<a class="btn btn-light edit" data-id="' . $row->id . '"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->grade . '\')"><i class="fa fa-edit"></i> 編輯</a><a class="btn btn-danger delete" data-id="' . $row->id . '" data-dept="' . $row->grade . '"><i class="fa fa-trash"></i> 刪除</a>';
            })
            ->addColumn('employees', function ($row) {
                return $row->count;
            })
            ->escapeColumns(['action'])
            ->toJson();
    }


    /**
     * Show the form for editing the specified grade.
     */
    public function create()
    {
        $competencies = Competency::select('id', 'competency')->get();
        return view('admin.grades.create', ['competencies' => $competencies]);
    }


    /**
     * Store a newly created grade in storage.
     */
    public function store(CreateRequest $request)
    {

        $grade = Grade::create([
            'grade' => $request->grade,
            'designation' => json_encode($request->designation),
            'competencyID' => $request->competency
        ]);

        foreach ($request->designation as $value) {
            if ($value !== '') {
                $designation = Designation::firstOrNew([
                    'grade_id' => $grade->id,
                    'designation' => $value
                ]);
                $designation->save();
            }
        }

        return Reply::success('<strong>' . $request->deptName . '</strong> successfully added to the Database');
    }


    public function edit($id)
    {
        $this->grade = Grade::with('designations')->findOrFail($id);
        $this->competencies = Competency::with(['grades' => function ($query) use ($id) {
            $query->where('id', $id);
        }])->get();

        return View::make('admin.grades.edit', $this->data);
    }

    /**
     * Update the specified department in storage.
     */
    public function update(UpdateRequest $request, Grade $grade)
    {
        $grade->update([
            'grade' => $request->grade,
            'designation' => $request->designation,
            'competencyID' => $request->competency,
        ]);

        $designations = $this->updateDesignations($request, $grade);

        $grade->designations()->saveMany($designations);

        return Reply::success("<strong>{$grade->deptName}</strong> updated successfully");
    }
    /**
     * Remove the specified department from storage.
     */
    public function destroy(Grade $grade)
    {

        if (request()->ajax()) {
            $grade->delete();
            return Reply::success('Deleted Successfully');
        }
    }

    public function ajax_designation()
    {
        if (Request::ajax()) {
            $input = request()->get('grade_id');
            $designation = Designation::where('grade_id', '=', $input)
                ->get();

            return Response::json($designation, 200);
        }
    }


    private function updateDesignations(UpdateRequest  $request, Grade $grade): Collection
    {
        $designations = collect();

        foreach ($request->designation as $index => $designation) {
            $designationId = $request->designationID[$index] ?? null;

            if (!empty($designation)) {
                if (!empty($designationId)) {
                    $existingDesignation = Designation::find($designationId);
                    if (!empty($existingDesignation)) {
                        $existingDesignation->designation = $designation;
                        $existingDesignation->save();
                        $designations->push($existingDesignation);
                    }
                } else {
                    $newDesignation = Designation::create([
                        'grade_id' => $grade->id,
                        'designation' => $designation,
                    ]);
                    $designations->push($newDesignation);
                }
            } else if (!empty($designationId)) {
                $existingDesignation = Designation::find($designationId);
                if (!empty($existingDesignation)) {
                    $existingDesignation->delete();
                }
            }
        }

        return $designations;
    }

    public function getGradesByDesignation()
    {
        $company = $this->company_id;
        $designations = $company->designations;
        $grades = $company->grades;

        $result = array();
        foreach ($designations as $designation) {
            $grades_array = array();
            foreach ($grades as $grade) {
                if ($grade->designation == $designation->designation) {
                    $grades_array[] = array(
                        'id' => $grade->id,
                        'grade' => $grade->grade,
                        'competency' => $grade->competency->competency,
                        'salary_max' => $grade->salary_max,
                        'salary_mid' => $grade->salary_mid,
                        'salary_min' => $grade->salary_min,
                    );
                }
            }

            $result[] = array(
                'designation' => $designation->designation,
                'grades' => $grades_array,
            );
        }

        return response()->json($result);
    }



    public function designation(\Illuminate\Http\Request $request)
    {
        $keyword = $request->input('keyword');

        $query = Designation::where('company_id', $this->company_id)
            ->where('designation', 'like', '%' . $keyword . '%');

        if (isset($request->current)) {
            $query->where('designation', '!=', $request->current);
        }

        $designations = $query->get(['id', 'designation'])->pluck('designation')->unique();

        return $designations;
    }
}
