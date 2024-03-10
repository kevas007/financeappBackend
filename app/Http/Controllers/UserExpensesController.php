<?php

namespace App\Http\Controllers;

use App\Models\UserExpenses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserExpensesRequest;
use App\Http\Requests\UpdateUserExpensesRequest;

class UserExpensesController extends Controller
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
    public function store(StoreUserExpensesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserExpenses $userExpenses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserExpenses $userExpenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserExpensesRequest $request, UserExpenses $userExpenses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserExpenses $userExpenses)
    {
        //
    }
}
