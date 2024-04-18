<?php

namespace Tests\Feature;

use App\Domain\Stores\StoreRepository;
use Tests\TestCase;
use App\Http\Controllers\StoreController;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class StoreControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method_returns_json_response(): void
    {
        $stores = Store::factory()->count(3)->create();

        $storeRepository = Mockery::mock(StoreRepository::class);

        $storeRepository->shouldReceive('getAll')->andReturn($stores);

        $storeController = new StoreController($storeRepository);

        $response = $storeController->index();

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = $response->getData(true);

        $this->assertCount(3, $responseData);

        $this->assertEquals($stores->toArray(), $responseData);
    }

    public function test_store_creates_new_store(): void
    {
        $request = Mockery::mock(StoreRequest::class);

        $request->shouldReceive('validated')->andReturn(['name' => 'Test Store', 'address' => 'Test Street, 123', 'active' => true]);

        $storeRepository = Mockery::mock(StoreRepository::class);

        $storeRepository->shouldReceive('create')->andReturn(new Store(['name' => 'Test Store', 'address' => 'Test Street, 123', 'active' => true]));

        $storeController = new StoreController($storeRepository);

        $response = $storeController->store($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_show_method_returns_json_response(): void
    {
        $store = Store::factory()->create();

        $storeRepository = Mockery::mock(StoreRepository::class);

        $storeRepository->shouldReceive('find')->with($store->id)->andReturn($store);

        $storeController = new StoreController($storeRepository);

        $response = $storeController->show($store->id);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_show_method_returns_404_when_book_not_found(): void
    {
        $storeRepository = Mockery::mock(StoreRepository::class);

        $storeRepository->shouldReceive('find')->andReturnNull();

        $storeController = new StoreController($storeRepository);

        $response = $storeController->show(999);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_update_store_method_returns_json_response(): void
    {
        $store = Store::factory()->create();

        $request = Mockery::mock(UpdateStoreRequest::class);

        $request->shouldReceive('validated')->andReturn(['name' => 'Updated store', 'address' => 'Test street, 123', 'active' => true]);

        $storeRepository = Mockery::mock(StoreRepository::class);

        $storeRepository->shouldReceive('find')->with($store->id)->andReturn($store);

        $storeRepository->shouldReceive('update')->with($store, ['name' => 'Updated store', 'address' => 'Test street, 123', 'active' => true]);

        $storeController = new StoreController($storeRepository);

        $response = $storeController->update($request, $store->id);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(200, $response->getStatusCode());
    }

}
