<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPassword;
use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ForgotPasswordController extends BaseController
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
        return Inertia::render('Auth/ForgotPassword', [
            'data' => [
                'title' => 'パスワード再発行申請',
                'request' => $request->all(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ForgotPassword $request)
    {
        $this->setFlash(__('メールを確認してパスワードをリセットしてください'));
        if (! $this->user->getUserByEmail($request)) {
            $this->setFlash(__('メールでユーザーを見つけることができません'), 'error');
        }
        if (! $this->user->forgotPassword($request)) {
            $this->setFlash(__('メールでユーザーを見つけることができません'), 'error');
        }

        return Inertia::render('Auth/ForgotPassword', parent::mergeSession([
            'data' => [
                'title' => 'パスワード再発行申請1',
                'request' => $request->all(),
            ],
        ]));
    }
}
