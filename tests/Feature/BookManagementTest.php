<?php

namespace Tests\Feature;

use App\Book;
use App\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BookManagementTest extends TestCase
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
        $response = $this->post('/books', $this->book_data());

        // $book->assertOk();
        $book = Book::first();
        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    public function test_book_title_is_required()
    {
        // $this->withoutExceptionHandling();

        $book = $this->post('/books', array_merge($this->book_data(), ['title' => '']));

        $book->assertSessionHasErrors('title');
    }

    public function test_book_author_is_required()
    {
        // $this->withoutExceptionHandling();

        $book = $this->post('/books', array_merge($this->book_data(), ['author_id' => '']));

        $book->assertSessionHasErrors('author_id');
    }

    public function test_can_update_book()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', $this->book_data());

        $book = Book::first();

        $response = $this->put('/books/' . $book->id, [
            'title' => 'New Title',
            'author_id' => 'Mohamed Gamal',
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);
        $this->assertCount(2, Author::all());

        $response->assertRedirect($book->fresh()->path());
    }

    public function test_a_book_can_be_deleted()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', $this->book_data());

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete('/books/' . $book->id);
        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }

    public function test_author_is_created_automatically()
    {
        $this->withoutExceptionHandling();
        $this->post('/books', $this->book_data());

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Book::all());
    }

    protected function book_data()
    {
        return [
            'title' => 'Good Title',
            'author_id' => 'Ahmed Gamal',
        ];
    }
}
