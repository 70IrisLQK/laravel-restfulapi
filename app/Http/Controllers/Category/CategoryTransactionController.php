<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        // $listTransaction = $category->products()
        //     ->whereHas('transactions')
        //     ->with('transactions')
        //     ->get()
        //     ->pluck('transactions')
        //     ->collapse();
        $listTransaction = $category->products()->with('transactions');

        return $this->showAll($listTransaction, 200, 'OK', 'Get transaction by category successfully.');
    }
}