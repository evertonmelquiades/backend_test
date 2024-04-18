<?php

namespace Tests\Unit\Controllers;

use App\Models\Book;
use App\Domain\Books\BookRepository;
use App\Http\Controllers\BookController;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Mockery;

class BookControllerTest extends TestCase
{
    public function test_index_returns_all_books(): void
    {
        $books = Book::factory(3)->create();

        $bookRepository = new BookRepository($books);

        $controller = new BookController($bookRepository);

        $response = $controller->index();

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);

        $responseData = $response->getData(true);

        $this->assertIsArray($responseData);
    }

    public function test_store_creates_new_book(): void
    {
        $request = Mockery::mock(StoreBookRequest::class);

        $request->shouldReceive('validated')->andReturn(['name' => 'Test Book', 'isbn' => '1234567890', 'value' => 20.99]);

        $bookRepository = Mockery::mock(BookRepository::class);

        $bookRepository->shouldReceive('create')->andReturn(new Book(['name' => 'Test Book', 'isbn' => '1234567890', 'value' => 20.99]));

        $bookController = new BookController($bookRepository);

        $response = $bookController->store($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(201, $response->getStatusCode());
    }


    public function test_show_method_returns_json_response(): void
    {
        $book = Book::factory()->create();

        $bookRepository = Mockery::mock(BookRepository::class);

        $bookRepository->shouldReceive('find')->with($book->id)->andReturn($book);

        $bookController = new BookController($bookRepository);

        $response = $bookController->show($book->id);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_show_method_returns_404_when_book_not_found(): void
    {
        $bookRepository = Mockery::mock(BookRepository::class);

        $bookRepository->shouldReceive('find')->andReturnNull();

        $bookController = new BookController($bookRepository);

        $response = $bookController->show(999);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_update_method_returns_json_response(): void
    {
        $book = Book::factory()->create();

        $request = Mockery::mock(UpdateBookRequest::class);

        $request->shouldReceive('validated')->andReturn(['name' => 'Updated Book', 'isbn' => '0987654321', 'value' => 30.99]);

        $bookRepository = Mockery::mock(BookRepository::class);

        $bookRepository->shouldReceive('find')->with($book->id)->andReturn($book);

        $bookRepository->shouldReceive('update')->with($book, ['name' => 'Updated Book', 'isbn' => '0987654321', 'value' => 30.99]);

        $bookController = new BookController($bookRepository);

        $response = $bookController->update($request, $book->id);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_update_method_returns_404_when_book_not_found(): void
    {
        $request = Mockery::mock(UpdateBookRequest::class);

        $request->shouldReceive('validated')->once()->andReturn(['name' => 'Updated Book', 'isbn' => '0987654321', 'value' => 30.99]);

        $bookRepository = Mockery::mock(BookRepository::class);

        $bookRepository->shouldReceive('find')->with(999)->andReturnNull();

        $bookController = new BookController($bookRepository);

        $response = $bookController->update($request, 999);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_destroy_method_deletes_book_and_returns_json_response(): void
    {
        $book = Book::factory()->create();

        $bookRepository = Mockery::mock(BookRepository::class);

        $bookRepository->shouldReceive('find')->with($book->id)->andReturn($book);

        $bookRepository->shouldReceive('delete')->with($book);

        $bookController = new BookController($bookRepository);

        $response = $bookController->destroy($book->id);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = $response->getData(true);

        $this->assertEquals('Book deleted successfully', $responseData['message']);
    }

    public function test_destroy_method_returns_404_when_book_not_found(): void
    {
        $bookRepository = Mockery::mock(BookRepository::class);

        $bookRepository->shouldReceive('find')->with(999)->andReturnNull();

        $bookController = new BookController($bookRepository);

        $response = $bookController->destroy(999);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(404, $response->getStatusCode());

        $responseData = $response->getData(true);

        $this->assertEquals('Book not found', $responseData['message']);
    }
}
