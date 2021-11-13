<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationTest extends TestCase
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

        $response->assertOk();

        $this->assertCount(1, Book::all());
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

        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'My title',
            'author' => 'Pavel'
        ]);

        $book = Book::first();

        $this->patch('/books/' . $book->id, [
            'title' => 'New title',
            'author' => 'New author'
        ]);

        $book = Book::first();

        $this->assertEquals('New title', $book->title);
        $this->assertEquals('New author', $book->author);
    }
}
