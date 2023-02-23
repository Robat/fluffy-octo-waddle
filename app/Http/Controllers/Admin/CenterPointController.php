<?php

namespace App\Http\Controllers\Admin;

use App\Models\ATest;
use App\Models\CDiff;
use App\Models\CTest;
use App\Models\PTest;
use App\Classes\Reply;
use App\Models\CenterPoint;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\CenterPoint\CreateRequest;
use App\Http\Requests\Admin\CenterPoint\UpdateRequest;


class CenterPointController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->atestOpen = 'active open';
        $this->pageTitle = 'Center Points';
        $this->atestActive = 'active';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.center_points.index', $this->data);
    }


    public function category(\Illuminate\Http\Request $request)
    {
        $this->category = $request->key;
        return View::make('admin.center_points.index', $this->data);
    }

    public function ajax_center_points($category = null)
    {
        $result = CenterPoint::select(['id', 'name', 'category', 'numbering', 'location', 'status', 'created_at', 'updated_at'])->where("category", $category);

        return DataTables::of($result)
            ->addIndexColumn()
            ->editColumn('status', function ($row) {
                $color = [0 => 'checked', 1 => ''];

                return '<input type="checkbox" onclick="change(' . $row->id . ',\'' . $row->status . '\')"' . $color[$row->status] . '>';
            })
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-light"  data-toggle="modal" onclick="showEdit(' . $row->id . ',\'' . $row->category . '\')"><i class="fa fa-edit"></i> Edit</a>

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
        $this->atests = ATest::where('frequency_id', admin()->company->frequency()->id)->get();
        $this->ctests = CTest::where('frequency_id', admin()->company->frequency()->id)->get();

        $this->ptests = PTest::where('frequency_id', admin()->company->frequency()->id)->get();


        return View::make('admin.center_points.create', $this->data);
    }


    public function categoryCreate($category)
    {
        $this->category = $category;
        $this->atests = ATest::where('frequency_id', admin()->company->frequency()->id)->get();
        if ($category == 'p') {
            $this->ctests = PTest::where('frequency_id', admin()->company->frequency()->id)->get();
        }
        if ($category == 'c') {
            $this->ctests = CTest::where('frequency_id', admin()->company->frequency()->id)->get();
        }

        return View::make('admin.center_points.create', $this->data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {

        $data = $this->modifyRequest($request);
        CenterPoint::create($data);
        // dd(explode("_", $data['location'])); //c_a

        if ($request->isCover == 0) {
            //1. atest
            $atest_all = ATest::where('frequency_id', admin()->company->frequency()->id)->orderBy('sort', 'ASC')->pluck('sort', 'id')->toArray();

            foreach ($atest_all as $key => $value) {
                $atest_all[$key] = array(
                    'frequency_id' => admin()->company->frequency()->id,
                    'a_sort' => $value,
                );
            }
            $cdiffs = CDiff::where('frequency_id', admin()->company->frequency()->id)->get();

            foreach ($cdiffs as $cdiff) {
                $cdiff->a_tests()->sync($atest_all);
            }


            //2. cdiff
            $cdiff_all = CDiff::where('frequency_id', admin()->company->frequency()->id)->pluck('sort', 'id')->toArray();
            foreach ($cdiff_all as $key => $value) {
                $cdiff_all[$key] = array(
                    'frequency_id' => admin()->company->frequency()->id,
                    'c_sort' => $value,
                );
            }

            $atests = ATest::where('frequency_id', admin()->company->frequency()->id)->get();

            foreach ($atests as  $value) {
                $value->c_diffs()->sync($cdiff_all);
            }
        }


        return Reply::success('<strong>OK</strong> successfully added to the Database');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CenterPoint  $centerPoint
     * @return \Illuminate\Http\Response
     */
    public function show(CenterPoint $centerPoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CenterPoint  $centerPoint
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $this->center_point = CenterPoint::find($id);

        // dd($this->center_point->a_point);

        $this->atests = ATest::where('frequency_id', admin()->company->frequency()->id)->get();
        if ($this->center_point->category == 'c') {
            $this->ctests = CTest::where('frequency_id', admin()->company->frequency()->id)->get();
        }
        if ($this->center_point->category == 'p') {
            $this->ctests = PTest::where('frequency_id', admin()->company->frequency()->id)->get();
        }
        return View::make('admin.center_points.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CenterPoint  $centerPoint
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {

        $center_point = CenterPoint::findOrFail($id);

        $center_point->update($this->modifyRequest($request));

        return Reply::success('<strong> ' . $request->name . '</strong> updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CenterPoint  $centerPoint
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Request::ajax()) {
            CenterPoint::destroy($id);
            return Reply::success('Deleted Successfully');
        }
    }

    public function changeStatus($id)
    {

        $center_point = CenterPoint::findOrFail($id);
        $status = ($center_point->status == 1) ? 0 : 1;
        $center_point->status =  $status;
        $center_point->save();

        return Reply::success('<strong> ' . $center_point->name . '</strong> updated successfully');
    }


    private function modifyRequest($request)
    {
        $data = $request->all();
        $data['a_point'] =  (int)explode('_', $request->location)[1];

        $data['c_point'] =  (int)explode('_', $request->location)[0];
        $data['p_point'] =  (int)explode('_', $request->location)[0];


        // strip_tags 去除標籤
        // $data['name'] = strip_tags($request->name);
        $data['name'] = filter_var($request->name, FILTER_SANITIZE_SPECIAL_CHARS);

        return $data;
    }
}
