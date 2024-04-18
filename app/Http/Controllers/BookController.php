<?php

namespace App\Http\Controllers;
use App\Domain\Books\BookRepository;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function index(): JsonResponse
    {
        $books = $this->bookRepository->getAll();
        return response()->json(BookResource::collection($books), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $book = $this->bookRepository->create($validated);

        return response()->json(new BookResource($book), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $book = $this->bookRepository->find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json(new BookResource($book), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();

        $book = $this->bookRepository->find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $this->bookRepository->update($book, $validated);

        return response()->json(new BookResource($book), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $book = $this->bookRepository->find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $this->bookRepository->delete($book);

        return response()->json(['message' => 'Book deleted successfully'], 200);
    }
}
