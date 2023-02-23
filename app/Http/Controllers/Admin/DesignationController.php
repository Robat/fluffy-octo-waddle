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


class DesignationController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->gradesOpen = 'active open';
        $this->pageTitle = '職等';
        $this->gradesActive = 'active';
    }

    // public function index()
    // {
    //     $designations = Designation::all();
    //     return view('admin.designations.index', compact('designations'));
    // }


    public function index()
    {

        $designations = Designation::select('id', 'designation');
        // dd($designations->get());
        return view('admin.designations.index');
    }

    public function getDesignations()
    {
        $designations = Designation::select('id', 'designation')->get();

        return DataTables::of($designations)

            ->make(true);
    }

    public function create()
    {
        return view('admin.designations.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'designation' => 'required|string|max:100',
        ]);

        $designation = new Designation();
        $designation->designation = $request->input('designation');
        $designation->save();

        return redirect()->route('designations.index')
            ->with('success', 'Designation created successfully');
    }

    public function edit(Designation $designation)
    {
        return view('admin.designations.edit', compact('designation'));
    }

    public function update(Request $request, Designation $designation)
    {
        $validatedData = $request->validate([
            'designation' => 'required|string|max:100',
        ]);

        $designation->designation = $request->input('designation');
        $designation->save();

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation updated successfully');
    }

    public function destroy(Designation $designation)
    {
        $designation->delete();

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation deleted successfully');
    }

    public function getGradesByDesignation($designation_id)
    {
        $designation = Designation::findOrFail($designation_id);
        $grades = Grade::where('designation', 'LIKE', '%' . $designation->designation . '%')->get();

        return view('admin.designations.grades', compact('designation', 'grades'));
    }
}
