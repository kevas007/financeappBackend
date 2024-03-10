<?php

namespace App\Http\Controllers;

use App\Models\ExpensesCategories;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpensesCategoriesRequest;
use App\Http\Requests\UpdateExpensesCategoriesRequest;

class ExpensesCategoriesController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpensesCategoriesRequest $request)
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
    public function show(ExpensesCategories $expensesCategories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpensesCategories $expensesCategories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpensesCategoriesRequest $request, ExpensesCategories $expensesCategories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpensesCategories $expensesCategories)
    {
        //
    }
}
