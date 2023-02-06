<?php

namespace App\Repositories\Product;

use App\Components\CommonComponent;
use App\Models\Product;
use App\Repositories\Product\ProductInterface;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;


class ProductRepository implements ProductInterface
{
    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function get($request)
    {
        $newSizeLimit = CommonComponent::newListLimit($request);
        $productBuilder = $this->product;
        if (isset($request['free_word']) && $request['free_word'] != '') {
            $productBuilder = $productBuilder->where(function ($q) use ($request) {
                $q->orWhere(CommonComponent::escapeLikeSentence('name', $request['free_word']));
            });
        }
        $products = $productBuilder->sortable(['updated_at' => 'desc'])->paginate($newSizeLimit);
        if (CommonComponent::checkPaginatorList($products)) {
            Paginator::currentPageResolver(function () {
                return 1;
            });
            $products = $productBuilder->paginate($newSizeLimit);
        }

        return $products;
    }

    public function store($request)
    {
        $product = $this->product->insert($request->all());
        if ($product) {
            return $product;
        }
        return false;
    }

    public function getById($id)
    {
        return $this->product->where('id', $id)->first();
    }

    public function update($request, $id)
    {
        $productInfo = $this->product->where('id', $id)->first();
        if (!$productInfo) {
            return false;
        }
        $productInfo->name = $request->name;
        $productInfo->amount = $request->amount;
        if ($request->type) {
            $productInfo->type = $request->type;
        }

       if($productInfo->save()){
        return $productInfo;
       }
       return false;
    }

    public function destroy($id)
    {
        $product = $this->product->findOrFail($id);
        if (!$product) {
            return false;
        }

        return $product->delete();
    }

    public function changeStatus($id, $status)
    {
        $product = $this->product->where('id', $id)->first();
        if (!$product) {
            return false;
        }

        if ($product->update(['status' => $status])) {
            return $product;
        }
        return false;
    }
}
