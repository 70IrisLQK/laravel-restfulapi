<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;

class TransactionSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Transaction $transaction)
    {
        $listSeller = $transaction->product->seller;

        return $this->showOne($listSeller, 200, 'OK', 'Get seller transaction successfully.');
    }
}