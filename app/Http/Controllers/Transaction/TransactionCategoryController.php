<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;

class TransactionCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Transaction $transaction)
    {
        $listCategory = $transaction->product->categories;

        return $this->showAll($listCategory, 200, 'OK', 'Get category by transaction successfully.');
    }
}