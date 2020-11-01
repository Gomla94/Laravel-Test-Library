<?php

namespace App\Http\Controllers;

use App\Book;
use Exception;
use Illuminate\Http\Request;

class CheckinBookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Book $book)
    {
        try {
            $user = auth()->user();
            $book->checkin($user);
        } catch (Exception $e) {
            return response([], 404);
        }
    }
}
