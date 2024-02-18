<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Str;
use App\Models\Stock;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $stock = Stock::all();
            $data = [
                'stock' => $stock
            ];
            return response()->json([
                'message' => 'Success get data stock',
                'success' => true,
                'data' => $data
            ], 200);
        }catch (Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat get data stock',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $request->validate([
            'book_id'=> 'required',
            // 'kode_buku'=> 'required',
            'status'=> 'required',
        ]);

        $book_id = $request->book_id;

        // Menghasilkan kode buku menggunakan timestamp dan string acak
        $kode_buku = 'BK-' . Str::random(5);

        $stock = new Stock();
        $stock->book_id = $request->book_id;
        $stock->kode_buku = $kode_buku;
        $stock->status = $request->status;
        $stock->save();

        $book_stock = Stock::where('book_id', $book_id)->count();
        // dd($book_stock);
        $book_stock_total = Book::where('id', $book_id)->firstOrFail();
        $book_stock_total->stock = $book_stock;
        $book_stock_total->save();

        DB::commit();
        return response()->json([
                'message' => 'Success add data stock',
                'success' => true,
                'data' => $stock
            ], 201);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'error' => 'Terjadi kesalahan saat get add stock',
                'detaxils' => $e->getMessage()
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
        DB::beginTransaction();
        try{
            $request->validate([
            'book_id'=> 'required',
            'kode_buku'=> 'required',
            'status'=> 'required',
        ]);

        $stock = Stock::findOrFail($id);

        $book_id = $request->book_id;

        $stock->book_id = $request->book_id;
        $stock->kode_buku = $request->kode_buku;
        $stock->status = $request->status;
        $stock->save();

        $book_stock = Stock::where('book_id', $book_id)->count();
        $book_stock_total = Book::findOrFail($book_id);
        $book_stock_total->stock = $book_stock;
        $book_stock_total->save();

        DB::commit();

        return response()->json([
                'message' => 'Success update data stock',
                'success' => true,
                'data' => $stock
            ], 200);
        }catch(Exception $e){
           return response()->json([
                'error' => 'Terjadi kesalahan saat update data stock',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try{
            // Cari dan hapus stok berdasarkan ID
        $stock = Stock::findOrFail($id); 
        $book_id = $stock->book_id; 
        $stock->delete();

        // Hitung ulang dan perbarui jumlah stok buku
        $book_stock_count = Stock::where('book_id', $book_id)->count();
        $book = Book::findOrFail($book_id);
        $book->stock = $book_stock_count;
        $book->save();

        DB::commit();

        $response = [
            'message' =>'Success delete data stock',
            'success' => true,
        ];
        return response()->json($response, 200);
        }catch(Exception $e){
            return response()->json([
                'error' => 'Terjadi kesalahan saat delete data stock',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
