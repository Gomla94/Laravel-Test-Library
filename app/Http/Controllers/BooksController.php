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
            'author_id' => 'required'
        ]);

        $book = Book::create($book_data);
        return redirect($book->path());
    }

    public function update(Book $book)
    {
        $book_data = request()->validate([
            'title' => 'required',
            'author_id' => 'required',
        ]);

        $book->update($book_data);
        return redirect($book->path());
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect('/books');
    }
}
