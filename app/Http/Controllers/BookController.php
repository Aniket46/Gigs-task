<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Book;

use App\Http\Controllers\Controller;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::all();

        return response()->json($books);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_name' => 'required',
            'author' => 'required',
            'cover_image' => 'required|mimes:png,jpg'
        ]);

        $fileName = time().'.'.$request->cover_image->extension();
        $hostwithHttp = request()->getSchemeAndHttpHost();
        $request->cover_image->move(public_path('uploads'),$fileName);
        $file_path = $hostwithHttp.'/uploads/'.$fileName;
        
        $newBook = new Book([
            'book_name' => $request->get('book_name'),
            'author' => $request->get('author'),
            'cover_image' => $file_path
        ]);

        $newBook->save();

        return response()->json($newBook);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return response()->json($book);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        $request->validate([
            'book_name' => 'required',
            'author' => 'required'
        ]);

        $book->book_name = $request->get('book_name');
        
        $book->author = $request->get('author');
        
        $book->save();

        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        $book->delete();

        return response()->json(Book::all());
    }
}
