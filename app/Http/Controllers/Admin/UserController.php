<?php

namespace App\Http\Controllers\Admin;

use App\Components\SearchQueryComponent;
use App\Enums\StatusCode;
use App\Http\Controllers\BaseController;
use App\Http\Requests\UserRequest;
use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserController extends BaseController
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
        $users = $this->user->get($request);
        $breadcrumbs = [
            ['name' => 'ユーザー一覧'],
        ];

        return Inertia::render('Admin/User/Index', [
            'breadcrumbs' => $breadcrumbs,
            'data' => [
                'title' => 'ユーザー一覧',
                'createUrl' => route('admin.user.create'),
                'listUrl' => route('admin.user.index'),
                'users' => $users->items(),
                'sortLinks' => $this->sortLinks('admin.user.index', [
                    ['key' => 'id', 'name' => 'ID'],
                    ['key' => 'name', 'name' => 'ユーザー名'],
                    ['key' => 'email', 'name' => 'メール'],
                ], $request),
                'request' => $request->all(),
                'paginator' => $this->paginator($users->appends(SearchQueryComponent::alterQuery($request))),
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
        $breadcrumbs = [
            ['name' => 'ユーザー一覧', 'url' => route('admin.user.index')],
            ['name' => 'ユーザー追加'],
        ];

        return Inertia::render('Admin/User/Form', [
            'breadcrumbs' => $breadcrumbs,
            'data' => [
                'title' => 'ユーザー追加',
                'urlAction' => route('admin.user.store'),
                'listUrl' => route('admin.user.index'),
                'checkEmailUrl' => route('admin.user.checkEmail'),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if ($this->user->store($request)) {
            $this->setFlash(__('代理店の新規作成が完了しました。'));

            return redirect()->route('admin.user.index');
        }
        $this->setFlash(__('エラーが発生しました。'), 'error');

        return redirect()->route('admin.user.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->user->getById($id);
        if (! $user) {
            return redirect()->route('admin.user.index');
        }
        $breadcrumbs = [
            ['name' => 'ユーザー一覧', 'url' => route('admin.user.index')],
            ['name' => 'ユーザー編集'],
        ];

        return Inertia::render('Admin/User/Form', [
            'breadcrumbs' => $breadcrumbs,
            'data' => [
                'title' => 'ユーザー編集',
                'urlAction' => route('admin.user.update', $id),
                'listUrl' => route('admin.user.index'),
                'isEdit' => true,
                'user' => $user,
                'checkEmailUrl' => route('admin.user.checkEmail'),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        if ($this->user->update($request, $id)) {
            $this->setFlash(__('代理店の新規作成が完了しました。'));

            return redirect()->route('admin.user.index');
        }
        $this->setFlash(__('エラーが発生しました。'), 'error');

        // return Inertia::render('Auth/ForgotPassword', parent::mergeSession([
        //     'data' => [
        //         'title' => 'パスワード再発行申請',
        //         'request' => $request->all(),
        //     ],
        // ]));
        // return $this->edit($id);

        return redirect()->route('admin.user.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->user->destroy($id)) {
            return response()->json([
                'message' => '管理者アカウントの削除が完了しました。',
            ], StatusCode::OK);
        }

        return response()->json([
            'message' => 'エラーが発生しました。',
        ], StatusCode::INTERNAL_ERR);
    }

    public function checkEmail(Request $request)
    {
        return response()->json([
            'valid' => $this->user->checkEmail($request),
        ], StatusCode::OK);
    }
}
