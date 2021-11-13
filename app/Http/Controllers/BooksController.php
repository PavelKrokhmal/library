<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;

class BooksController extends Controller
{
    public function store(StoreBookRequest $request) {
        Book::create($request->validated());
    }

    public function update(UpdateBookRequest $request, Book $book) {
        $book->update($request->validated());
    }
}
