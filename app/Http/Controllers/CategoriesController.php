<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoriesRequest;
use App\Http\Requests\UpdateCategoriesRequest;
use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = auth()->user();

            $data = DB::table('categories')
                ->where('user_id', $user->id)
             
                ->get();
            
        
            // Crée une nouvelle catégorie avec les données validées
        
            
            return response()->json([
                'message' => 'catégories get successfuly',
                'status' => 200,
                'categories' => $data
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
     * Display a listing of the resource.
     */
    public function allExpenses(){
        try {
            $user = auth()->user();
            
            $currentMonthStart = Carbon::now()->startOfMonth();
            $currentMonthEnd = Carbon::now()->endOfMonth();

            $data = DB::table('expenses')
            ->join('user_expenses', 'expenses.id', '=', 'user_expenses.expense_id')
            ->join('expenses_categories as ec', 'expenses.id', '=', 'ec.expense_id')
            ->join('categories as c', 'c.id', '=', 'ec.category_id')
            ->where('user_expenses.user_id', $user->id)
            ->whereBetween('expenses.created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('expenses.price');
        
        // Réinitialisation du total à 0 à minuit le dernier jour du mois
        if (Carbon::now()->isSameDay($currentMonthEnd)) {
            $totalExpenses = 0;
        }
        
 
            // Crée une nouvelle catégorie avec les données validées
        
            
            return response()->json([
                'message' => 'expenses get successfuly',
                'status' => 200,
                'expenses' => $data,
                'current_month' => Carbon::now()->format('F Y')
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
     * Show the form for creating a new resource.
     */
    public function MonthlyExpenses()
    {   
        try {
            $user = auth()->user();
            
            $currentMonthStart = Carbon::now()->startOfMonth();
            $currentMonthEnd = Carbon::now()->endOfMonth();

            $data = DB::table('expenses')
            ->join('user_expenses', 'expenses.id', '=', 'user_expenses.expense_id')
            ->join('expenses_categories as ec', 'expenses.id', '=', 'ec.expense_id')
            ->join('categories as c', 'c.id', '=', 'ec.category_id')
            ->where('user_expenses.user_id', $user->id)
            ->whereBetween('expenses.created_at', [$currentMonthStart, $currentMonthEnd])
            ->selectRaw('MONTH(expenses.created_at) as month, SUM(expenses.price) as total')
            ->groupByRaw('MONTH(expenses.created_at)')
            ->get();
        
        // Réinitialisation du total à 0 à minuit le dernier jour du mois
 
            // Crée une nouvelle catégorie avec les données validées
        
            
            return response()->json([
                'message' => 'expenses get successfuly',
                'status' => 200,
                'expenses' => $data,
                'current_month' => Carbon::now()->format('F Y')
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
       
        try {
           
            $data = $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string', // La description n'est pas obligatoire
                'check' => 'nullable|boolean', // Le champ 'check' est optionnel et doit être un booléen
            ]);
            
            $user = auth()->user();
       
            // Crée une nouvelle catégorie avec les données validées
            $categorie = Categories::create([
                'name' => $data['name'],
                'description' => $data['description']  ?? null,
                'check' => $data['check'] ?? false,
                'user_id' => $user->id // Utilisation de l'ID de l'utilisateur actuellement authentifié
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
        $category = Categories::find($id);
        try {
           
    
            if (!$category) {
                return response()->json([
                    'message' => 'Category not found',
                    'status' => 404
                ], 404);
            }
    
            $data = $request->all();
    
            // Vérifier si le champ 'name' est présent dans les données de la requête
            if (isset($data['name'])) {
                $category->name = $data['name'];
            }
    
            // Vérifier si le champ 'description' est présent dans les données de la requête
            if (isset($data['description'])) {
                $category->description = $data['description'];
            }
    
            $category->save();
    
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
