<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Borrow;
use App\Models\History;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Exception;

class BorrowController extends Controller
{
    public function index()
    {
        try{
            $borrow = Borrow::all();
        $data = [
            'borrow' => $borrow
        ];
        return response()->json([
                'message' => 'Success get data peminjaman',
                'success' => true,
                'data' => $data
            ], 201);
        }catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Terjadi kesalahan saat menampilkan data peminjaman',
                'details' => $e->getMessage()
            ], 500); // Internal Server Error
        }
        
    }

    public function create()
    {
        // Implementasi sesuai kebutuhan
    }

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
            $kode_buku = $request->kode_buku;

            $borrow = new Borrow();
            $borrow->user_id = $user_id;
            $borrow->kode_buku_id = $kode_buku;
            $borrow->save();

            $detail = Stock::where('id', $kode_buku)->firstOrFail();
            $detail->status = 'Not available';
            $detail->save();

            DB::commit();

            return response()->json([
                'message' => 'Peminjaman berhasil disimpan',
                'success' => true,
                'data' => $borrow
            ], 201); 
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan peminjaman',
                'details' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }

    public function show(string $id)
    {
        // Implementasi sesuai kebutuhan
    }

    public function edit(string $id)
    {
        // Implementasi sesuai kebutuhan
    }

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
            $kode_buku = $request->kode_buku_id;

            $borrow = Borrow::find($id);

             // Jika kode buku berubah, set status buku lama menjadi 'Available'
            if ($borrow->kode_buku_id != $kode_buku) {
                $oldStock = Stock::where('id', $borrow->kode_buku_id)->firstOrFail();
                $oldStock->status = 'Available';
                $oldStock->save();

                // Set status buku baru menjadi 'Not Available'
                $newStock = Stock::where('id', $kode_buku)->firstOrFail();
                $newStock->status = 'Not Available';
                $newStock->save();
            }
            
            $borrow->user_id = $user_id;
            $borrow->kode_buku_id = $kode_buku;
            $borrow->save();

            

            DB::commit();

            return response()->json([
                'message' => 'Peminjaman berhasil diedit',
                'success' => true,
                'data' => $borrow
            ], 200); // OK
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Terjadi kesalahan saat memperbarui peminjaman',
                'details' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }

    public function destroy(string $id)
    {
        try{
            $borrow = Borrow::find($id);
            $status = Stock::where('id', $borrow->kode_buku_id)->firstOrFail();
            $status->status = 'Available';
            $status->save();
        $borrow->delete();

        $response = [
            'message' =>'Success delete data peminjaman',
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
