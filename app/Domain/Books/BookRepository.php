<?php

namespace App\Domain\Books;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

class BookRepository
{
    /**
     * Get all books.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Book::all();
    }

    /**
     * Create a new book.
     *
     * @param array $data
     * @return Book
     */
    public function create(array $data): Book
    {
        return Book::create($data);
    }

    /**
     * Find a book by ID.
     *
     * @param int $id
     * @return Book|null
     */
    public function find(int $id): ?Book
    {
        return Book::find($id);
    }

    /**
     * Update an existing book.
     *
     * @param Book $book
     * @param array $data
     * @return void
     */
    public function update(Book $book, array $data): void
    {
        $book->update($data);
    }

    /**
     * Delete a book.
     *
     * @param Book $book
     * @return void
     */
    public function delete(Book $book): void
    {
        $book->delete();
    }
}
