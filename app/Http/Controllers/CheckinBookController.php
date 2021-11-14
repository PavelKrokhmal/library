<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;

class CheckinBookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Book $book)
    {
        try {
            $book->checkin(auth()->user());
        } catch (Exception $exception) {
            return response([], 404);
        }
    }
}
