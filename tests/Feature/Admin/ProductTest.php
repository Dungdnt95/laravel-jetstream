<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Laravel\Jetstream\Features;

class ProductTest extends TestCase
{
    use DatabaseTransactions;
    private $model;

    public function setUp(): void
    {
        parent::setUp();
        $this->setAuthUser();
    }

    private function setAuthUser()
    {
        $user = User::where('email', 'admin@gmail.com')->first();
        $this->actingAs($user, 'admin');
    }

    private function fakeData()
    {
        return  $this->model = Product::factory()->create();
    }

    protected function dataStruct()
    {
        return
            [
                'message',
                'data' => [
                    [
                        'id',
                        'name',
                        'type',
                        'status',
                        'amount',
                        'updated_at',
                        'created_at',
                    ]
                ]
            ];
    }

    private function actionApi($dataInput, $uri, $method)
    {
        return $this->json(
            $method,
            $uri,
            $dataInput
        );
    }

    protected function sendActionRequest(array $dataInput, $statusCode = 200, $uri, $method)
    {
        $response = $this->actionApi($dataInput, $uri, $method);
        $response->assertStatus($statusCode);
        $response->assertSuccessful();
        $response->assertOk();
        // $response->assertJsonStructure($this->dataStruct());
    }

    protected function sendActionRequestRedirect(array $dataInput, $statusCode = 200, $uri, $method,$redirect)
    {
        $response = $this->actionApi($dataInput, $uri, $method);
        $response->assertRedirect($redirect);
    }

    public function test_active_product_by_id()
    {
        $product_id = $this->fakeData()->id;
        $uri = '/admin/product' . '/' . $product_id . '/update-status';
        $this->sendActionRequest(['status' => 1], 200, $uri, 'post');
    }

    public function test_destroy_product_api()
    {
        $product_id = $this->fakeData()->id;
        $uri = '/admin/product' . '/' . $product_id;
        $this->sendActionRequest([], 200, $uri, 'delete');
    }

    public function test_update_product_api()
    {
        $redirect = '/admin/product';
        $product_id = $this->fakeData()->id;
        $uri = '/admin/product' . '/' . $product_id;
        $this->sendActionRequestRedirect(['name'=>'test','amount'=>100], 200, $uri, 'put',$redirect);
    }

    public function test_store_product_api()
    {
        $redirect = '/admin/product';
        $uri = '/admin/product';
        $this->sendActionRequestRedirect([
            "name" => 'producttest',
            "type" => 1,
            "amount" => 100,
            "status" => rand(0,1),
        ], 200, $uri, 'post',$redirect);
    }

}
