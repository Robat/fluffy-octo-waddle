<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;

use App\Models\PValue;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\PValue\CreateRequest;
use App\Http\Requests\Admin\PValue\UpdateRequest;

class PValueController extends AdminBaseController
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
    public function index()
    {
        return View::make('admin.pvalues.index', $this->data);
    }



    public function ajax_pvalues()
    {
        $result = PValue::select(['id', 'name', 'numbering', 'mark', 'sort', 'status', 'center', 'created_at', 'updated_at']);

        return DataTables::of($result)
            ->addIndexColumn()
            ->editColumn('center', function ($row) {
                $color = [0 => '', 1 => 'checked'];

                return '<input type="checkbox" onclick="changeCenter(' . $row->id . ',\'' . $row->center . '\')"' . $color[$row->center] . '>';
            })
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
    public function create()
    {
        return View::make('admin.pvalues.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {

        $CValueCount = PValue::select('id', 'frequency_id', 'name', 'sort')->where('frequency_id', admin()->company->frequency()->id)->where('status', 1)->orderBy('sort', 'ASC')->get()->toArray();
        $data = $this->modifyRequest($request);

        $data['sort'] = count($CValueCount);
        PValue::create($data);

        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }


    public function show(PValue $companyPValue)
    {
        //
    }


    public function edit($id)
    {
        $this->pvalue = PValue::find($id);
        return View::make('admin.pvalues.edit', $this->data);
    }


    public function update(UpdateRequest $request, $id)
    {

        $pvalue = PValue::findOrFail($id);
        $data = $this->modifyRequest($request);
        $pvalue->update($data);

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }




    public function destroy($id)
    {
        if (Request::ajax()) {
            PValue::destroy($id);
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
        $pvalue_default = PValue::Where('center', '<>', '0');
        $pvalue_default->update(
            ['center' => 0]
        );

        $pvalue = PValue::findOrFail($id);
        $center = ($pvalue->center == 1) ? 0 : 1;
        $pvalue->center = $center;
        $pvalue->save();

        return Reply::success('<strong> ' . '' . '</strong> updated successfully');
    }
}
