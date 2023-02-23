<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Classes\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Controllers\AdminBaseController;

class AdminLoginController extends AdminBaseController
{


    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        if (Auth::guard('admin')->check()) {

            return Redirect::route('member.dashboard.index');
        }

        return view('admin.login', $this->data);
    }

    public function ajaxAdminLogin(LoginRequest $request)
    {

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (Auth::guard('admin')->attempt($data, true)) {
            $user = admin();
            // $user->last_login = Carbon::now();
            $user->save();
            return redirect()->route('member.dashboard.index');
        }
        return 'error';
    }


    public function login()
    {
        return 'index';
    }
    public function logout()
    {
        Auth::guard('admin')->logout();

        return Redirect::route('member.getlogin');
    }
}
