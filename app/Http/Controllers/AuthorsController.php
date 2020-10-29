<?php

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    public function store()
    {
        $author_data = request()->validate([
            'name' => 'required',
            'dob' => 'required',
        ]);

        $author = Author::create($author_data);
    }
}
