<?php

namespace App\Http\Controllers;
use App\Models\Categories;
use App\Models\Expenses;
use App\Models\ExpensesCategories;
use App\Models\UserExpenses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpensesRequest;
use App\Http\Requests\UpdateExpensesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpensesController extends Controller
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
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'description' => 'required|string',
                'category_id' => 'required',
                'category_id.*' => 'exists:categories,id', // Assurez-vous que chaque catégorie existe dans la table des catégories
            ]);
    
            // Crée une nouvelle dépense
            $expense = Expenses::create([
                'name' => $data['name'],
                'price' => $data['price'],
                'description' => $data['description'],
            ]);
            
            // Attache les catégories à la dépense dans la table pivot 'expenses_categories'
          
            ExpensesCategories::create([
                    'expense_id' => $expense->id,
                    'category_id' => $data['category_id'],
                ]);
            
            // Récupère l'utilisateur actuellement authentifié
            $user = auth()->user();
            
            // Attache la dépense à l'utilisateur dans la table pivot 'user_expenses'
            UserExpenses::create([
                'expense_id' => $expense->id,
                'user_id' => $user->id,
            ]);
    
            return response()->json([
                'message' => 'Expense created successfully',
                'status' => 200,
                'expense' => $expense
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Expenses $expenses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expenses $expenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'description' => 'required|string',
                'category_id' => 'required',
                'category_id.*' => 'exists:categories,id', // Assurez-vous que chaque catégorie existe dans la table des catégories
            ]);
    
            // Récupère la dépense existante
            $expense = Expenses::findOrFail($id);
    
            // Met à jour les attributs de la dépense
            $expense->update([
                'name' => $data['name'],
                'price' => $data['price'],
                'description' => $data['description'],
            ]);
    
            // Met à jour les catégories associées à la dépense dans la table pivot 'expenses_categories'
            $cartex = DB::table('expenses_categories')
                                        ->where('expense_id', $id)
                                        ->first();
            if ($cartex) {
                        DB::table('expenses_categories')
                                ->where('expense_id', $id)
                                ->update([
                                        'category_id' => $data['category_id']
                                                ]);
                                        }
            return response()->json([
                'message' => 'Expense updated successfully',
                'status' => 200,
                'expense' => $expense
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
    public function destroy(Expenses $expenses)
    {
        //
    }
}
