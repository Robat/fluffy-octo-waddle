<?php

namespace App\Http\Controllers\Admin;

use App\Models\ADiff;
use App\Classes\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminBaseController;

class ADiffCDiffController extends AdminBaseController
{
    public function index()
    {
        $this->adiffs = ADiff::with('c_diffs')->get();

        return view('admin.ac.index', $this->data);
    }
}
