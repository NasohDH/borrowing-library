<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\ResponseHelper;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with(['category', 'authors'])->get();
        return ResponseHelper::success(' جميع الكتب', $books);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->except(['cover', 'authors']));

        if ($request->hasFile('cover')){
            $file = $request->file('cover');
            $filename = $request->ISBN . '.' . $file->extension();
            Storage::putFileAs('book-images', $file, $filename);
            $book->cover = $filename;
            $book->save();
        }

        $book->authors()->attach($request->authors);

        $book->load(['category', 'authors']);

        return ResponseHelper::success("تمت إضافة الكتاب", $book);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load(['category', 'authors']);
        return ResponseHelper::success("تفاصيل الكتاب", $book);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->except(['cover', 'authors']));

        if ($request->hasFile('cover')) {
            if ($book->cover) {
                Storage::delete('book-images/' . $book->cover);
            }
            
            $file = $request->file('cover');
            $filename = $request->ISBN . '.' . $file->extension();
            Storage::putFileAs('book-images', $file, $filename);
            $book->cover = $filename;
            $book->save();
        }

        $book->authors()->sync($request->authors);

        $book->load(['category', 'authors']);

        return ResponseHelper::success("تم تعديل الكتاب", $book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->cover) {
            Storage::delete('book-images/' . $book->cover);
        }

        $book->delete();
        return ResponseHelper::success("تم حذف الكتاب", $book);
    }
}