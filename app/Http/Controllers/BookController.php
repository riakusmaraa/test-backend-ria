<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Exception;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $book = Book::All();
            $data = [
                'book' => $book
            ];
    
            return response()->json([
                'message' => 'Success get data buku',
                'success' => true,
                'data' => $data
            ], 201);
        }catch (Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat get data buku',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
        $request->validate([
                    'category_id' => 'required',
                    'judul' => 'required',
                    'cover' => 'required',
                    'penerbit' => 'required',
                    'penulis' => 'required',
                    'sinopsis' => 'required',
                ]);

                $book = new Book();
                $book->category_id = $request->category_id;
                $book->judul = $request->judul;
                $book->cover = $request->cover;
                $book->penerbit = $request->penerbit;
                $book->penulis = $request->penulis;
                $book->sinopsis = $request->sinopsis;
                $book->save();

                return response()->json([
                'message' => 'Success add data buku',
                'success' => true,
                'data' => $book
            ], 201);
        }catch(Exception $e){
            return response()->json([
                'error' => 'Terjadi kesalahan saat add data buku',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $book = Book::find($id);
            $request->validate([
                'category_id' => 'required',
                'judul' => 'required',
                'cover' => 'required',
                'penerbit' => 'required',
                'penulis' => 'required',
                'sinopsis' => 'required',
                'stock' => 'required',
            ]);
            
            $book->category_id = $request->category_id;
            $book->judul = $request->judul;
            $book->cover = $request->cover;
            $book->penerbit = $request->penerbit;
            $book->penulis = $request->penulis;
            $book->sinopsis = $request->sinopsis;
            $book->stock = $request->stock;
            $book->save();


            return response()->json([
                'message' => 'Success update data buku',
                'success' => true,
                'data' => $book
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat add data buku',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $book = Book::find($id);
        $book->delete();

        $response = [
            'message' =>'Success hapus data buku',
            'success' => true,
        ];
        return response()->json($response, 200);
        }catch(Exception $e){
            return response()->json([
                'error' => 'Terjadi kesalahan saat delete data buku',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
