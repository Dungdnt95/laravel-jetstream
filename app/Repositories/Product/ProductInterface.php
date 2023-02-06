<?php

namespace App\Repositories\Product;

interface ProductInterface
{
    public function get($request);

    public function getById($id);

    public function store($request);

    public function update($request, $id);

    public function destroy($id);

    public function changeStatus($id,$status);
}
