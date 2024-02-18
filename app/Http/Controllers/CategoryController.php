<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Exception;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $category = Category::All();
            $data = [
                'category' => $category
            ];

            return response()->json([
                'message' => 'Success get data kategori',
                'success' => true,
                'data' => $data
            ], 201);
        }catch (Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat get data category',
                'details' => $e->getMessage()
            ], 500); // Internal Server Error
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
                    'name' => 'required',
                ]);

                $category = new Category();
                $category->name = $request->name;
                $category->save();

                $response = [
                    'message' =>'Success add data category',
                    'success' => true,
                    'data' => $category
                ];
                return response()->json($response, 201);
        }catch(Exception $e){
            return response()->json([
                'error' => 'Terjadi kesalahan saat add data category',
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
        try{
            $category = Category::find($id);

            $request->validate([
                'name' => 'required',
            ]);

            $category->name = $request->name;
            $category->save();

            $response = [
                'message' =>'Success update data category',
                'success' => true,
                'data' => $category
            ];
            return response()->json($response, 200);
        }catch(Exception $e){
            return response()->json([
                'error' => 'Terjadi kesalahan saat update data category',
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
            $category = Category::find($id);
            $category->delete();

            $response = [
                'message' =>'Success delete data category',
                'success' => true,
            ];
            return response()->json($response, 200);
        }catch(Exception $e){
            return response()->json([
                'error' => 'Terjadi kesalahan saat delete data category',
                'details' => $e->getMessage()
            ], 500); // Internal Server Error
        }

    }
}
