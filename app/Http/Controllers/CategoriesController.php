<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoriesRequest;
use App\Http\Requests\UpdateCategoriesRequest;
use Illuminate\Http\Request;
class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    
       
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string'
            ]);
    
            // Crée une nouvelle catégorie avec les données validées
            $categorie = Categories::create([
                'name' => $data['name'],
                'description' => $data['description']
            ]);
            
            return response()->json([
                'message' => 'Category created successfully',
                'status' => 200,
                'category' => $categorie
            ], 200);
        } catch (\Exception $e) {
            // Log the error or handle it accordingly
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        try {
            $category = Categories::findOrFail($id);
    
            return response()->json([
                'message' => 'Category retrieved successfully',
                'status' => 200,
                'category' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categories $categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $category = Categories::findOrFail($id);
    
            $data = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string'
            ]);
    
            $category->update($data);
    
            return response()->json([
                'message' => 'Category updated successfully',
                'status' => 200,
                'category' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $category = Categories::findOrFail($id);
            $category->delete();
    
            return response()->json([
                'message' => 'Category deleted successfully',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
