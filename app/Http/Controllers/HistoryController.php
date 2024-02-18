<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\History;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $history = History::all();
        $data = [
            'history' => $history
        ];
        return response()->json($data, 200);
        }catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Terjadi kesalahan saat menampilkan data pengembalian',
                'details' => $e->getMessage()
            ], 500); // Internal Server Error
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
        $auth = Auth::user();
        if (!$auth) {
            return response()->json([
                'error' => 'Silakan login terlebih dahulu'
            ], 401); // Unauthorized
        }

        DB::beginTransaction();
        try {
            $user_id = $auth->id;
            $kode_buku = $request->kode_buku_id;
            // $tgl_kembali = $request->tgl_kembali;

            $history = new History();
            $history->user_id = $user_id;
            // $history->tgl_kembali = $tgl_kembali;
            $history->kode_buku_id = $kode_buku;
            $history->save();

            $detail = Stock::where('id', $kode_buku)->firstOrFail();
            $detail->status = 'Available';
            $detail->save();

            // buat query untuk hapus data peminjaman
            $borrow = Borrow::where('id', $kode_buku)->where('user_id', $user_id)->first();
            if ($borrow) {
                $borrow->delete();
            }

            DB::commit();

            return response()->json([
                'message' => 'Pengembalian buku berhasil ',
                'data' => $history
            ], 201); // Created
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan data pengembalian',
                'details' => $e->getMessage()
            ], 500); // Internal Server Error
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
        $auth = Auth::user();
        if (!$auth) {
            return response()->json([
                'error' => 'Silakan login terlebih dahulu'
            ], 401); // Unauthorized
        }

        DB::beginTransaction();
        try {
            $user_id = $auth->id;
            $kode_buku = $request->kode_buku;
            $tgl_kembali = $request->tgl_kembali;

            $history = History::find($id);
            $history->user_id = $user_id;
            $history->tgl_kembali = $tgl_kembali;
            $history->kode_buku = $kode_buku;
            $history->save();

            $detail = Stock::where('id', $kode_buku)->firstOrFail();
            $detail->status = 'Available';
            $detail->save();

            DB::commit();

            return response()->json([
                'message' => 'Pengembalian buku berhasil diedit ',
                'data' => $history
            ], 201); // Created
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengubah data pengembalian',
                'details' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $history = History::find($id);
        $history->delete();

        $response = [
            'message' =>'Success Hapus Data',
            'success' => true,
        ];
        return response()->json($response, 200);
        }catch(Exception $e){
            return response()->json([
                'errors' => $e->getMessage(),

            ], 400);
        }
    
    }
}
