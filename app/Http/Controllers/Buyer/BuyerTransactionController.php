<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;

class BuyerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $listTransaction = $buyer->transactions;

        return $this->showAll($listTransaction, 200, 'OK', 'Get transaction by buyers successfully.');
    }
}