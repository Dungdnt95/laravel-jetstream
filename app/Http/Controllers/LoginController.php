<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class LoginController extends BaseController
{
    private $user;
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect(route('admin.dashboard.index'));
        }
        return Inertia::render('Auth/Login', [
            'data' => [
                'title' => 'ログイン',
                'request' => $request->all(),
                'urlForgot' => route('forgot-password.index')
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials, $request->remember_me ?? false)) {
            if (!$this->user->saveLoginHistory()) {
                Auth::guard('admin')->logout();
                return redirect('/');
            }
            return redirect($request->url_redirect ? $request->url_redirect : route('admin.dashboard.index'));
        }
        return Inertia::render('Auth/Login', [
            'data' => [
                'title' => 'ログイン',
                'message' => 'ログインIDとパスワードが一致しません。',
                'request' => $request->all()
            ],
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect(route('login.index'));
    }
}
