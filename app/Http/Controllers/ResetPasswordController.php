<?php

namespace App\Http\Controllers;

use App\Http\Requests\InitPassChange;
use App\Repositories\User\UserInterface;
use App\Http\Requests\ForgotPassword;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ResetPasswordController extends BaseController
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
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        if (! $this->user->getUserByToken($id)) {
            $this->setFlash(__('期限切れのリンク'), 'error');
            return Inertia::render('Auth/ForgotPassword', [
                'data' => [
                    'title' => 'パスワード再設定期限外',
                    'request' => $request->all()
                ],
            ]);
        }
        return Inertia::render('Auth/ResetPassword', [
            'data' => [
                'title' => 'パスワード再設定',
                'request' => $request->all(),
                'urlUpdate' => route('init-password.update', $id)
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InitPassChange $request, $id)
    {
        $this->setFlash(__('パスワード変更が完了しました'));
        if (! $this->user->getUserByToken($id)) {
            $this->setFlash(__('期限切れのリンク'), 'error');
        }
        if (! $this->user->updatePasswordByToken($request, $id)) {
            $this->setFlash(__('期限切れのリンク'), 'error');
        }
        return redirect()->route('login.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
