<?php

namespace Tests\Feature;

use App\Models\Author;
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
        $response = $this->post('/books', $this->data());

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
        $response = $this->post('/books', array_merge($this->data(), [
            'author_id' => ''
        ]));

        $response->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function a_book_can_be_updated() {
        $this->post('/books', $this->data());

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'New title',
            'author_id' => 'New author'
        ]);

        $book = $book->fresh();

        $this->assertEquals('New title', $book->title);
        $this->assertEquals(2, $book->author_id);

        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_book_can_be_deleted() {
        $this->post('/books', $this->data());

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }

    /** @test */
    public function a_new_author_is_automatically_added()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'My title',
            'author_id' => 'Pavel'
        ]);

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    public function data()
    {
        return [
            'title' => 'title',
            'author_id' => 'Pavel'
        ];
    }
}
