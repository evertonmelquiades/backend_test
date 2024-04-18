<?php

namespace App\Domain\Stores;

use App\Models\Book;
use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;

class StoreRepository
{
    /**
     * Get all Stores.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Store::all();
    }

    /**
     * Create a new Store.
     *
     * @param array $data
     * @return Store
     */
    public function create(array $data): Store
    {
        return Store::create($data);
    }

    /**
     * Find a Store by ID.
     *
     * @param int $id
     * @return Store|null
     */
    public function find(int $id): ?Store
    {
        return Store::find($id);
    }

    /**
     * Update an existing Store.
     *
     * @param Store $store
     * @param array $data
     * @return void
     */
    public function update(Store $store, array $data): void
    {
        $store->update($data);
    }

    /**
     * Delete a Store.
     *
     * @param Store $store
     * @return void
     */
    public function delete(Store $store): void
    {
        $store->delete();
    }

    /**
     * Associate a book with a store.
     *
     * @param int $storeId
     * @param int $bookId
     * @return void
     */
    public function associateBook(int $storeId, int $bookId): void
    {
        $store = Store::findOrFail($storeId);
        $book = Book::findOrFail($bookId);

        $store->books()->attach($book);
    }
}
