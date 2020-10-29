<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function store()
    {
        $book_data = request()->validate([
            'title' => 'required',
            'author' => 'required'
        ]);

        Book::create($book_data);
    }

    public function update(Book $book)
    {
        $book_data = request()->validate([
            'title' => 'required',
            'author' => 'required',
        ]);

        $book->update($book_data);
    }
}
