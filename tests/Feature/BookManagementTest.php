<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_added_to_the_library()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/books', [
            'title' => 'title',
            'author' => 'Pavel'
        ]);

        $this->assertCount(1, Book::all());

        $book = Book::first();
        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_title_is_required() {
        $response = $this->post('/books', [
            'title' => '',
            'author' => 'Pavel'
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_author_is_required() {
        $response = $this->post('/books', [
            'title' => 'My title',
            'author' => ''
        ]);

        $response->assertSessionHasErrors('author');
    }

    /** @test */
    public function a_book_can_be_updated() {
        $this->post('/books', [
            'title' => 'My title',
            'author' => 'Pavel'
        ]);

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'New title',
            'author' => 'New author'
        ]);

        $book = $book->fresh();

        $this->assertEquals('New title', $book->title);
        $this->assertEquals('New author', $book->author);

        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_book_can_be_deleted() {
        $this->post('/books', [
            'title' => 'My title',
            'author' => 'Pavel'
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }
}
