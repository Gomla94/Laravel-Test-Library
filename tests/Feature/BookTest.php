<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{

    // Session is missing expected key [errors]. means there are no errors.
    // If you have the data is invalid error remove $this->withoutExceptionHandling().

    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_book_can_be_added()
    {
        // $this->withoutExceptionHandling();
        $response = $this->post('/books', [
            'title' => 'this is the first book',
            'author' => 'Ahmed Gamal',
        ]);

        // $book->assertOk();
        $book = Book::first();
        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    public function test_book_title_is_required()
    {
        // $this->withoutExceptionHandling();

        $book = $this->post('/books', [
            'title' => '',
            'author' => 'Ahmed Gamal',
        ]);

        $book->assertSessionHasErrors('title');
    }

    public function test_book_author_is_required()
    {
        // $this->withoutExceptionHandling();

        $book = $this->post('/books', [
            'title' => 'This is a book',
            'author' => ''
        ]);

        $book->assertSessionHasErrors('author');
    }

    public function test_can_update_book()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Good Title',
            'author' => 'Ahmed Gamal',
        ]);

        $book = Book::first();

        $response = $this->put('/books/' . $book->id, [
            'title' => 'New Title',
            'author' => 'Mohamed Gamal',
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('Mohamed Gamal', Book::first()->author);

        $response->assertRedirect($book->fresh()->path());
    }

    public function test_a_book_can_be_deleted()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Book title',
            'author' => 'Ahmed Gamal',
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete('/books/' . $book->id);
        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }
}
