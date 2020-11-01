<?php

namespace Tests\Unit;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_author_id_is_recorded()
    {
        Book::create([
            'title' => 'New Book',
            'author_id' => 'Ahmed',
        ]);

        $this->assertCount(1, Book::all());
    }
}
