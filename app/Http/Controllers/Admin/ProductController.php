<?php

namespace App\Http\Controllers\Admin;

use App\Components\SearchQueryComponent;
use App\Enums\OperationType;
use App\Enums\StatusCode;
use App\Http\Controllers\BaseController;
use App\Repositories\Product\ProductInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProductController extends BaseController
{
    private $product;

    public function __construct(ProductInterface $product)
    {
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = $this->product->get($request);
        $breadcrumbs = [
            ['name' => 'ユーザー一覧'],
        ];

        return Inertia::render('Admin/Product/Index', [
            'breadcrumbs' => $breadcrumbs,
            'data' => [
                'title' => 'ユーザー一覧',
                'createUrl' => route('admin.product.create'),
                'products' => $products->items(),
                'sortLinks' => $this->sortLinks('admin.product.index', [
                    ['key' => 'id', 'name' => 'ID'],
                    ['key' => 'name', 'name' => 'ユーザー名'],
                ], $request),
                'request' => $request->all(),
                'paginator' => $this->paginator($products->appends(SearchQueryComponent::alterQuery($request))),
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
            ['name' => 'ユーザー一覧', 'url' => route('admin.product.index')],
            ['name' => 'ユーザー追加'],
        ];

        return Inertia::render('Admin/Product/Form', [
            'breadcrumbs' => $breadcrumbs,
            'data' => [
                'title' => 'ユーザー追加',
                'listUrl' => route('admin.product.index'),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($this->product->store($request)) {
            $this->saveOperationLog($request);
            $this->setFlash(__('代理店の新規作成が完了しました。'));

            return redirect()->route('admin.product.index');
        }
        $this->setFlash(__('エラーが発生しました。'), 'error');

        return redirect()->route('admin.product.create');
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
        $product = $this->product->getById($id);
        if (!$product) {
            return redirect()->route('admin.product.index');
        }
        $breadcrumbs = [
            ['name' => 'ユーザー一覧', 'url' => route('admin.product.index')],
            ['name' => 'ユーザー編集'],
        ];

        return Inertia::render('Admin/Product/Form', [
            'breadcrumbs' => $breadcrumbs,
            'data' => [
                'title' => 'ユーザー編集',
                'listUrl' => route('admin.user.index'),
                'isEdit' => true,
                'product' => $product,
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
    public function update(Request $request, $id)
    {
        if ($this->product->update($request, $id)) {
            $this->saveOperationLog($request, OperationType::UPDATE);
            $this->setFlash(__('代理店の新規作成が完了しました。'));

            return redirect()->route('admin.product.index');
        }
        $this->setFlash(__('エラーが発生しました。'), 'error');

        return redirect()->route('admin.product.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if ($this->product->destroy($id)) {
            $this->saveOperationLog($request, OperationType::DELETE);

            return response()->json([
                'message' => '管理者アカウントの削除が完了しました。',
            ], StatusCode::OK);
        }

        return response()->json([
            'message' => 'エラーが発生しました。',
        ], StatusCode::INTERNAL_ERR);
    }

    // update Status

    public function updateStatus($id, Request $request)
    {
        $data = $this->product->changeStatus($id,$request->status);
        if ($data) {
            $this->saveOperationLog($request, OperationType::DELETE);

            return response()->json([
                'message' => '管理者アカウントの削除が完了しました。',
                'data'=>[$data]
            ], StatusCode::OK);
        }

        return response()->json([
            'message' => 'エラーが発生しました。',
        ], StatusCode::INTERNAL_ERR);
    }
}
