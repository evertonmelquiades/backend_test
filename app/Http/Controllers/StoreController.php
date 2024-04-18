<?php

namespace App\Http\Controllers;

use App\Domain\Stores\StoreRepository;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Http\Resources\StoreResource;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    private $storeRepository;

    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public function index(): JsonResponse
    {
        $stores = $this->storeRepository->getAll();
        return response()->json(StoreResource::collection($stores), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $store = $this->storeRepository->create($validated);

        return response()->json(new StoreResource($store), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $store = $this->storeRepository->find($id);

        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        return response()->json(new StoreResource($store), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreRequest $request, $id): JsonResponse
    {
        $store = $this->storeRepository->find($id);

        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        $validated = $request->validated();

        $this->storeRepository->update($store, $validated);

        return response()->json(new StoreResource($store), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $store = $this->storeRepository->find($id);

        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        $this->storeRepository->delete($store);

        return response()->json(['message' => 'Store deleted successfully'], 200);
    }

    /**
     * Associate a book a store
     */
    public function associateBook($storeId, $bookId)
    {
        $this->storeRepository->associateBook($storeId, $bookId);

        return response()->json(['message' => 'Book associated with store successfully'], 200);
    }

    /**
     * List all books of a store.
     */
    public function listBooks($storeId)
    {
        $store = $this->storeRepository->find($storeId);

        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        $books = $store->books()->select('books.id', 'books.name')->get();

        $response = [
            'store' => $store,
            'books' => $books,
        ];

        return response()->json($response, 200);
    }
}
